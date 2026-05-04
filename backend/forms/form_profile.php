<?php
require(__DIR__.'/../header.php');

session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}
?>

<main>
  <a href="/student014/boci/backend/forms/form_address.php">Dirección de envíos</a>
  <a href="/student014/boci/backend/forms/form_payment.php">Opciones de pago</a>
  <a href="/student014/boci/backend/forms/form_account.php">Gestionar mi cuenta</a>
  <a href="/student014/boci/backend/forms/orders.php">Mis pedidos</a>
  <a href="/student014/boci/backend/db/db_logout.php">Log out</a>
  <button class="btnShoppingCart">
    <img src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
</main>

<?php
require(__DIR__.'/../footer.php');
?>