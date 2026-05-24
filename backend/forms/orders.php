<?php
require(__DIR__.'/../header.php');

// session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}
?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img draggable="false" src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="profile">
    <div class="pages">
      <p><a href="/student014/boci/backend/forms/form_address.php">DIRECCIÓN DE ENVÍO</a></p>
      <p><a href="/student014/boci/backend/forms/form_payment.php">OPCIONES DE PAGO</a></p>
      <p><a href="/student014/boci/backend/forms/form_account.php">GESTIONAR MI CUENTA</a></p>
      <p><a href="/student014/boci/views/cart.html">MI CARRITO</a></p>
      <p><a href="/student014/boci/backend/forms/orders.php">MIS PEDIDOS</a></p>
    </div>
    <h2>ORDERS</h2>
    <?php

    require(__DIR__ . '/../config/db_config.php');

    $customerId = $_SESSION['customer_id'];

    $stmt = $conn->prepare("
      SELECT
        o.order_id,
        o.order_number,
        o.total,
        o.order_status,
        o.placed_on,

        oi.quantity,
        oi.product_unit_price,

        p.product_name,
        p.product_image

      FROM boci_orders AS o

      INNER JOIN boci_order_items AS oi
        ON o.order_id = oi.order_id

      INNER JOIN boci_products AS p
        ON oi.product_id = p.product_id

      WHERE o.customer_id = ?

      ORDER BY o.placed_on DESC, o.order_id DESC
    ");

    if (!$stmt) {
      echo "<p>No se pudieron cargar los pedidos.</p>";
      $conn->close();
      exit();
    }

    $stmt->bind_param("i", $customerId);

    if (!$stmt->execute()) {
      echo "<p>No se pudieron cargar los pedidos.</p>";
      $stmt->close();
      $conn->close();
      exit();
    }

    $result = $stmt->get_result();

    $orders = [];

    while ($row = $result->fetch_assoc()) {

      $orderNumber = $row['order_number'];

      if (!isset($orders[$orderNumber])) {
        $orders[$orderNumber] = [
          'order_number' => $row['order_number'],
          'total' => $row['total'],
          'order_status' => $row['order_status'],
          'placed_on' => $row['placed_on'],
          'items' => []
        ];
      }

      $orders[$orderNumber]['items'][] = [
        'product_name' => $row['product_name'],
        'product_image' => $row['product_image'],
        'quantity' => $row['quantity'],
        'product_unit_price' => $row['product_unit_price']
      ];
    }

    $stmt->close();
    $conn->close();

    ?>

    <section class="orders-container">

    <?php if (empty($orders)): ?>

      <p>No has realizado ningún pedido todavía.</p>

    <?php else: ?>

      <?php foreach ($orders as $order): ?>

        <article class="order-card">

          <div class="order-header">
            <h3><?= htmlspecialchars($order['order_number']) ?></h3>
            <p><?= htmlspecialchars($order['placed_on']) ?></p>
            <p><?= htmlspecialchars($order['order_status']) ?></p>
            <strong><?= number_format($order['total'], 2) ?>€</strong>
          </div>

          <?php foreach ($order['items'] as $item): ?>

            <article class="cart-item order-item">

              <img
                draggable="false"
                class="plush"
                src="<?= htmlspecialchars($item['product_image']) ?>"
                alt="<?= htmlspecialchars($item['product_name']) ?>"
              >

              <div class="quantity">
                <p><?= htmlspecialchars($item['product_name']) ?></p>

                <div class="quantity-button">
                  <span class="quantity-product">
                    <?= (int) $item['quantity'] ?>
                  </span>
                </div>
              </div>

              <span class="price">
                <?= number_format($item['product_unit_price'], 2) ?>€
              </span>

            </article>

          <?php endforeach; ?>

        </article>

      <?php endforeach; ?>

    <?php endif; ?>

    </section>

  </div>

  <button class="btnShoppingCart">
    <img draggable="false" src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
  <button class="btnLogOut">
    <img draggable="false" class="icon" src="/student014/boci/assets/icons/logout-icon-black.svg" alt="log-out-icon">
  </button>
</main>

<?php
require(__DIR__.'/../footer.php');
?>