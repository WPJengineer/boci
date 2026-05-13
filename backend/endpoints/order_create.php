<?php

header("Content-Type: application/json");

session_start();

if (!isset($_SESSION['customer_id'])) {
  http_response_code(401);

  echo json_encode([
    'success' => false,
    'message' => 'User not authenticated'
  ]);

  exit;
}

$customerId = $_SESSION['customer_id'];

require(__DIR__ . '/../config/db_config.php');

$conn->begin_transaction();

/*
  1. Get selected address and transport fee
*/

$stmtAddress = $conn->prepare("
  SELECT
    a.address_id,
    tf.transport_fee_value
  FROM boci_address AS a
  INNER JOIN boci_transport_fee AS tf
    ON a.transport_fee_id = tf.transport_fee_id
  WHERE a.customer_id = ?
    AND a.selected = 1
  LIMIT 1
");

if (!$stmtAddress) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare address statement'
  ]);

  $conn->close();
  exit;
}

$stmtAddress->bind_param("i", $customerId);

if (!$stmtAddress->execute()) {
  $stmtAddress->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not execute address query'
  ]);

  $conn->close();
  exit;
}

$resultAddress = $stmtAddress->get_result();
$address = $resultAddress->fetch_assoc();

$stmtAddress->close();

if (!$address) {
  $conn->rollback();
  http_response_code(400);

  echo json_encode([
    'success' => false,
    'message' => 'Necesitas seleccionar una dirección de envío.'
  ]);

  $conn->close();
  exit;
}

$addressId = (int) $address['address_id'];
$transportFee = (float) $address['transport_fee_value'];

/*
  2. Get selected payment method
*/

$stmtPaymentMethod = $conn->prepare("
  SELECT
    payment_method_id,
    method_type
  FROM boci_payment_methods
  WHERE customer_id = ?
    AND is_default = 1
    AND is_active = 1
  LIMIT 1
");

if (!$stmtPaymentMethod) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare payment method statement'
  ]);

  $conn->close();
  exit;
}

$stmtPaymentMethod->bind_param("i", $customerId);

if (!$stmtPaymentMethod->execute()) {
  $stmtPaymentMethod->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not execute payment method query'
  ]);

  $conn->close();
  exit;
}

$resultPaymentMethod = $stmtPaymentMethod->get_result();
$paymentMethod = $resultPaymentMethod->fetch_assoc();

$stmtPaymentMethod->close();

if (!$paymentMethod) {
  $conn->rollback();
  http_response_code(400);

  echo json_encode([
    'success' => false,
    'message' => 'Necesitas seleccionar un método de pago.'
  ]);

  $conn->close();
  exit;
}

$paymentMethodId = (int) $paymentMethod['payment_method_id'];
$methodType = $paymentMethod['method_type'];

/*
  3. Get cart products
*/

$stmtCart = $conn->prepare("
  SELECT
    sc.product_id,
    sc.quantity,
    p.product_unit_price
  FROM boci_shopping_cart AS sc
  INNER JOIN boci_products AS p
    ON sc.product_id = p.product_id
  WHERE sc.customer_id = ?
");

if (!$stmtCart) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare cart statement'
  ]);

  $conn->close();
  exit;
}

$stmtCart->bind_param("i", $customerId);

if (!$stmtCart->execute()) {
  $stmtCart->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not execute cart query'
  ]);

  $conn->close();
  exit;
}

$resultCart = $stmtCart->get_result();

$cartItems = [];
$subtotal = 0;

while ($row = $resultCart->fetch_assoc()) {
  $quantity = (int) $row['quantity'];

  if ($quantity <= 0) {
    continue;
  }

  $productId = (int) $row['product_id'];
  $unitPrice = (float) $row['product_unit_price'];

  $subtotal += $quantity * $unitPrice;

  $cartItems[] = [
    'product_id' => $productId,
    'quantity' => $quantity,
    'product_unit_price' => $unitPrice
  ];
}

$stmtCart->close();

if (count($cartItems) === 0) {
  $conn->rollback();
  http_response_code(400);

  echo json_encode([
    'success' => false,
    'message' => 'Tu carrito está vacío.'
  ]);

  $conn->close();
  exit;
}

$total = $subtotal + $transportFee;

/*
  4. Insert order
*/

$stmtOrder = $conn->prepare("
  INSERT INTO boci_orders
  (
    order_number,
    customer_id,
    address_id,
    subtotal,
    transport_fee,
    total,
    order_status
  )
  VALUES (NULL, ?, ?, ?, ?, ?, 'paid')
");

if (!$stmtOrder) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare order statement'
  ]);

  $conn->close();
  exit;
}

$stmtOrder->bind_param(
  "iiddd",
  $customerId,
  $addressId,
  $subtotal,
  $transportFee,
  $total
);

if (!$stmtOrder->execute()) {
  $stmtOrder->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not create order'
  ]);

  $conn->close();
  exit;
}

$orderId = $conn->insert_id;
$stmtOrder->close();

/*
  5. Generate order number
*/

$orderNumber = "BOCI-" . date("Ymd") . "-" . str_pad($orderId, 6, "0", STR_PAD_LEFT);

$stmtUpdateOrder = $conn->prepare("
  UPDATE boci_orders
  SET order_number = ?
  WHERE order_id = ?
");

if (!$stmtUpdateOrder) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare order number statement'
  ]);

  $conn->close();
  exit;
}

$stmtUpdateOrder->bind_param("si", $orderNumber, $orderId);

if (!$stmtUpdateOrder->execute()) {
  $stmtUpdateOrder->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not update order number'
  ]);

  $conn->close();
  exit;
}

$stmtUpdateOrder->close();

/*
  6. Insert order items
*/

$stmtItem = $conn->prepare("
  INSERT INTO boci_order_items
  (
    order_id,
    product_id,
    quantity,
    product_unit_price
  )
  VALUES (?, ?, ?, ?)
");

if (!$stmtItem) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare order items statement'
  ]);

  $conn->close();
  exit;
}

foreach ($cartItems as $item) {
  $stmtItem->bind_param(
    "iiid",
    $orderId,
    $item['product_id'],
    $item['quantity'],
    $item['product_unit_price']
  );

  if (!$stmtItem->execute()) {
    $stmtItem->close();
    $conn->rollback();
    http_response_code(500);

    echo json_encode([
      'success' => false,
      'message' => 'Could not insert order item'
    ]);

    $conn->close();
    exit;
  }
}

$stmtItem->close();

/*
  7. Insert payment
*/

$amountCents = (int) round($total * 100);

$stmtPayment = $conn->prepare("
  INSERT INTO boci_payments
  (
    order_number,
    customer_id,
    payment_method_id,
    provider,
    method_type,
    amount_cents,
    currency,
    payment_status,
    paid_at
  )
  VALUES (?, ?, ?, 'mock', ?, ?, 'EUR', 'paid', NOW())
");

if (!$stmtPayment) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare payment statement'
  ]);

  $conn->close();
  exit;
}

$stmtPayment->bind_param(
  "siisi",
  $orderNumber,
  $customerId,
  $paymentMethodId,
  $methodType,
  $amountCents
);

if (!$stmtPayment->execute()) {
  $stmtPayment->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not create payment'
  ]);

  $conn->close();
  exit;
}

$paymentId = $conn->insert_id;
$stmtPayment->close();

/*
  8. Insert payment event
*/

$payload = json_encode([
  'order_number' => $orderNumber,
  'amount_cents' => $amountCents,
  'currency' => 'EUR',
  'status' => 'paid'
]);

$stmtEvent = $conn->prepare("
  INSERT INTO boci_payment_events
  (
    payment_id,
    provider,
    event_type,
    payload
  )
  VALUES (?, 'mock', 'payment.paid', ?)
");

if (!$stmtEvent) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare payment event statement'
  ]);

  $conn->close();
  exit;
}

$stmtEvent->bind_param("is", $paymentId, $payload);

if (!$stmtEvent->execute()) {
  $stmtEvent->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not create payment event'
  ]);

  $conn->close();
  exit;
}

$stmtEvent->close();

/*
  9. Clear shopping cart
*/

$stmtDeleteCart = $conn->prepare("
  DELETE FROM boci_shopping_cart
  WHERE customer_id = ?
");

if (!$stmtDeleteCart) {
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare cart delete statement'
  ]);

  $conn->close();
  exit;
}

$stmtDeleteCart->bind_param("i", $customerId);

if (!$stmtDeleteCart->execute()) {
  $stmtDeleteCart->close();
  $conn->rollback();
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not clear cart'
  ]);

  $conn->close();
  exit;
}

$stmtDeleteCart->close();

$conn->commit();

echo json_encode([
  'success' => true,
  'message' => 'Pedido creado correctamente.',
  'order_number' => $orderNumber
], JSON_UNESCAPED_UNICODE);

$conn->close();

exit;

?>