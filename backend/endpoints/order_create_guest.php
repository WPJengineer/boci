<?php

header("Content-Type: application/json");
session_start();

require(__DIR__ . '/../config/db_config.php');

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
  http_response_code(400);
  echo json_encode([
    "success" => false,
    "message" => "Datos inválidos."
  ]);
  exit;
}

$personal = $input["personal_info"] ?? [];
$address = $input["address"] ?? [];
$identification = trim($address["identification"] ?? "");
$payment = $input["payment"] ?? [];
$cart = $input["cart"] ?? [];

if (
  empty($personal["name"]) ||
  empty($personal["lastname"]) ||
  empty($personal["email"]) ||
  empty($personal["gender"]) ||
  empty($address["address-line1"]) ||
  empty($address["postal_code"]) ||
  empty($address["city"]) ||
  empty($address["country"]) ||
  empty($payment["method_type"]) ||
  !is_array($cart) ||
  count($cart) === 0
) {
  http_response_code(400);
  echo json_encode([
    "success" => false,
    "message" => "Faltan datos para crear el pedido."
  ]);
  exit;
}

$conn->begin_transaction();

try {
  /*
    1. Create guest customer
  */

  $stmtFindGuest = $conn->prepare("
    SELECT customer_id
    FROM boci_customers
    WHERE customer_email = ?
        AND customer_role = 'guest'
    LIMIT 1
  ");

  $stmtFindGuest->bind_param("s", $personal["email"]);
  $stmtFindGuest->execute();

  $existingGuest = $stmtFindGuest->get_result()->fetch_assoc();
  $stmtFindGuest->close();

  if ($existingGuest) {
    $customerId = (int) $existingGuest["customer_id"];
  } else {
    $guestPassword = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);

    $stmtCustomer = $conn->prepare("
        INSERT INTO boci_customers
        (
        customer_forename,
        customer_lastname,
        customer_role,
        customer_email,
        customer_password,
        customer_gender,
        customer_privacy,
        customer_newsletter
        )
        VALUES (?, ?, 'guest', ?, ?, ?, 1, 0)
    ");

    $stmtCustomer->bind_param(
        "sssss",
        $personal["name"],
        $personal["lastname"],
        $personal["email"],
        $guestPassword,
        $personal["gender"]
    );

    $stmtCustomer->execute();
    $customerId = $conn->insert_id;
    $stmtCustomer->close();
  }

  /*
    2. Decide transport fee
  */

  $country = $address["country"];

  if ($country === "ES") {
    $transportZone = "national";
  } else {
    $transportZone = "international";
  }

  $stmtTransport = $conn->prepare("
    SELECT transport_fee_id, transport_fee_value
    FROM boci_transport_fee
    WHERE transport_zone = ?
    LIMIT 1
  ");

  $stmtTransport->bind_param("s", $transportZone);
  $stmtTransport->execute();

  $transport = $stmtTransport->get_result()->fetch_assoc();
  $stmtTransport->close();

  if (!$transport) {
    throw new Exception("No se pudo calcular el transporte.");
  }

  $transportFeeId = (int) $transport["transport_fee_id"];
  $transportFee = (float) $transport["transport_fee_value"];

  /*
    3. Insert guest address
  */

  $street = $address["address-line1"];
  $number = $address["address-line2"] ?? null;
  $city = $address["city"];
  $state = $address["state"] ?? "";
  $postalCode = $address["postal_code"];
  $country = $address["country"];

  $stmtAddress = $conn->prepare("
    INSERT INTO boci_address
    (
      customer_id,
      transport_fee_id,
      street,
      number,
      city,
      state,
      postal_code,
      country,
      selected
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
  ");

  $stmtAddress->bind_param(
    "iissssss",
    $customerId,
    $transportFeeId,
    $street,
    $number,
    $city,
    $state,
    $postalCode,
    $country
  );

  $stmtAddress->execute();
  $addressId = $conn->insert_id;
  $stmtAddress->close();

  /*
    4. Insert guest payment method
  */

  $methodType = $payment["method_type"];
  $providerPaymentMethodId = "guest_pm_" . uniqid();

  $cardLast4 = null;
  $expMonth = null;
  $expYear = null;

  if ($methodType === "card") {
    $cardNumber = preg_replace("/\D/", "", $payment["card_num"] ?? "");
    $cardLast4 = substr($cardNumber, -4);
    $expMonth = (int) ($payment["exp_month"] ?? 0);
    $expYear = (int) ($payment["exp_year"] ?? 0);
  }

  $stmtPaymentMethod = $conn->prepare("
    INSERT INTO boci_payment_methods
    (
      customer_id,
      provider,
      provider_payment_method_id,
      method_type,
      card_last4,
      exp_month,
      exp_year,
      is_default,
      is_active
    )
    VALUES (?, 'mock', ?, ?, ?, ?, ?, 1, 1)
  ");

  $stmtPaymentMethod->bind_param(
    "isssii",
    $customerId,
    $providerPaymentMethodId,
    $methodType,
    $cardLast4,
    $expMonth,
    $expYear
  );

  $stmtPaymentMethod->execute();
  $paymentMethodId = $conn->insert_id;
  $stmtPaymentMethod->close();

  /*
    5. Get product prices from DB
  */

  $cartItems = [];
  $subtotal = 0;

  $stmtProduct = $conn->prepare("
    SELECT product_id, product_unit_price
    FROM boci_products
    WHERE product_id = ?
  ");

  foreach ($cart as $cartItem) {
    $productId = (int) ($cartItem["product_id"] ?? 0);
    $quantity = (int) ($cartItem["quantity"] ?? 0);

    if ($productId <= 0 || $quantity <= 0) {
      continue;
    }

    $stmtProduct->bind_param("i", $productId);
    $stmtProduct->execute();

    $product = $stmtProduct->get_result()->fetch_assoc();

    if (!$product) {
      continue;
    }

    $unitPrice = (float) $product["product_unit_price"];
    $subtotal += $unitPrice * $quantity;

    $cartItems[] = [
      "product_id" => $productId,
      "quantity" => $quantity,
      "product_unit_price" => $unitPrice
    ];
  }

  $stmtProduct->close();

  if (count($cartItems) === 0) {
    throw new Exception("Tu carrito está vacío.");
  }

  $total = $subtotal + $transportFee;

  /*
    6. Insert order
  */

  $stmtOrder = $conn->prepare("
    INSERT INTO boci_orders
    (
      order_number,
      customer_id,
      address_id,
      customer_identification,
      subtotal,
      transport_fee,
      total,
      order_status
    )
    VALUES (NULL, ?, ?, ?, ?, ?, ?, 'paid')
  ");

  $stmtOrder->bind_param(
    "iisddd",
    $customerId,
    $addressId,
    $identification,
    $subtotal,
    $transportFee,
    $total
  );

  $stmtOrder->execute();
  $orderId = $conn->insert_id;
  $stmtOrder->close();

  $orderNumber = "BOCI-" . date("Ymd") . "-" . str_pad($orderId, 6, "0", STR_PAD_LEFT);

  $stmtUpdateOrder = $conn->prepare("
    UPDATE boci_orders
    SET order_number = ?
    WHERE order_id = ?
  ");

  $stmtUpdateOrder->bind_param("si", $orderNumber, $orderId);
  $stmtUpdateOrder->execute();
  $stmtUpdateOrder->close();

  /*
    7. Insert order items
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

  foreach ($cartItems as $item) {
    $stmtItem->bind_param(
      "iiid",
      $orderId,
      $item["product_id"],
      $item["quantity"],
      $item["product_unit_price"]
    );

    $stmtItem->execute();
  }

  $stmtItem->close();

  /*
    8. Insert payment
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

  $stmtPayment->bind_param(
    "siisi",
    $orderNumber,
    $customerId,
    $paymentMethodId,
    $methodType,
    $amountCents
  );

  $stmtPayment->execute();
  $paymentId = $conn->insert_id;
  $stmtPayment->close();

  /*
    9. Insert payment event
  */

  $payload = json_encode([
    "order_number" => $orderNumber,
    "amount_cents" => $amountCents,
    "currency" => "EUR",
    "status" => "paid",
    "customer_type" => "guest"
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

  $stmtEvent->bind_param("is", $paymentId, $payload);
  $stmtEvent->execute();
  $stmtEvent->close();

  $conn->commit();

  echo json_encode([
    "success" => true,
    "message" => "Pedido creado correctamente.",
    "order_number" => $orderNumber
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  $conn->rollback();

  http_response_code(500);

  echo json_encode([
    "success" => false,
    "message" => $e->getMessage()
  ], JSON_UNESCAPED_UNICODE);
}

$conn->close();
exit;

?>