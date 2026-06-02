<?php

require(__DIR__.'/../header.php');

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

if (empty($_GET['order_number'])) {
  header("Location: /student014/boci/backend/forms/orders.php");
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];
$orderNumber = trim($_GET['order_number']);

$stmt = $conn->prepare("
  SELECT
    o.order_number,
    o.placed_on,
    o.total,
    o.order_status,

    a.street,
    a.number,
    a.city,
    a.state,
    a.postal_code,
    a.country,

    pay.method_type,
    pay.payment_status

  FROM boci_orders AS o

  INNER JOIN boci_address AS a
    ON o.address_id = a.address_id

  LEFT JOIN boci_payments AS pay
    ON o.order_number = pay.order_number

  WHERE o.order_number = ?
    AND o.customer_id = ?

  LIMIT 1
");

if (!$stmt) {
  header("Location: /student014/boci/backend/forms/orders.php");
  exit();
}

$stmt->bind_param("si", $orderNumber, $customerId);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

$stmt->close();
$conn->close();

if (!$order) {
  header("Location: /student014/boci/backend/forms/orders.php");
  exit();
}

$paymentMethod = 'No disponible';

if (($order['method_type'] ?? '') === 'card') {
  $paymentMethod = 'Tarjeta';
} elseif (($order['method_type'] ?? '') === 'google_pay') {
  $paymentMethod = 'Google Pay';
}

$address = trim(
  ($order['street'] ?? '') . ' ' .
  ($order['number'] ?? '') . ', ' .
  ($order['postal_code'] ?? '') . ' ' .
  ($order['city'] ?? '') . ', ' .
  ($order['state'] ?? '') . ', ' .
  ($order['country'] ?? '')
);

?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img draggable="false" src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="pages">
    <p><a href="/student014/boci/backend/forms/form_address.php">DIRECCIÓN DE ENVÍO</a></p>
    <p><a href="/student014/boci/backend/forms/form_payment.php">OPCIONES DE PAGO</a></p>
    <p><a href="/student014/boci/backend/forms/form_account.php">GESTIONAR MI CUENTA</a></p>
    <p><a href="/student014/boci/views/cart.html">MI CARRITO</a></p>
    <p><a href="/student014/boci/backend/forms/orders.php">MIS PEDIDOS</a></p>
  </div>

  <div class="order-confirm">
    <img class="success" src="/student014/boci/assets/images/order_success_image.png" alt="order-success-image">
    <h1>¡Pedido confirmado!</h1>
    <p>Tu pedido se ha realizado correctamente.</p>
    <div class="order-num-main">
      <p>Número de pedido</p>
      <span><?= htmlspecialchars($order['order_number']) ?></span>
    </div>
    <div class="order-email">
      <img src="/student014/boci/assets/icons/purple-email-icon.svg" alt="email-icon">
      <p>Te hemos enviado un correo electrónico con el recibo y los detalles de tu pedido.</p>
    </div>
    <div class="order-details">
      <p>Resumen de pedido</p>
      <div class="order-date">
        <div>
          <img src="/student014/boci/assets/icons/calender-icon.svg" alt="calender-icon">
          <p>Fecha</p>
        </div>
        <span><?= htmlspecialchars($order['placed_on']) ?></span>
      </div>
      <div class="order-num">
        <div>
          <img src="/student014/boci/assets/icons/receipt-icon.svg" alt="receipt-icon">
          <p>Número de pedido</p>
        </div>
        <span><?= htmlspecialchars($order['order_number']) ?></span>
      </div>
      <div class="order-payment">
        <div>
          <img src="/student014/boci/assets/icons/wallet-icon.svg" alt="wallet-icon">
          <p>Método de pago</p>
        </div>
        <span><?= htmlspecialchars($paymentMethod) ?></span>
      </div>
      <div class="order-address">
        <div>
          <img src="/student014/boci/assets/icons/truck-icon.svg" alt="delivery-icon">
          <p>Dirección de envío</p>
        </div>
        <span><?= htmlspecialchars($address) ?></span>
      </div>
      <div class="order-subtotal">
        <p>Total pagado</p>
        <span><?= number_format((float)$order['total'], 2) ?>€</span>
      </div>
    </div>
    <p>Prepararemos tu pedido lo antes posible. Puedes consultar el estado del pedido en <a href="/student014/boci/backend/forms/orders.php">Mis pedidos</a>.</p>
    <div class="order-buttons">
      <button type="button" class="view">VER MIS PEDIDOS</button>
      <button type="button" class="follow">SEGUIR COMPRANDO</button>
    </div>
  </div>

  <button class="btnShoppingCart">
    <img draggable="false" src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
  <button type="button" class="btnLogOut">
    <img draggable="false" class="icon" src="/student014/boci/assets/icons/logout-icon-black.svg" alt="log-out-icon">
  </button>
</main>

<?php
require(__DIR__.'/../footer.php');
?>