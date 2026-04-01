<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <a href="/boci/backend/forms/form_address.php">Dirección de envíos</a>
  <a href="/boci/backend/forms/form_payment.php">Opciones de pago</a>
  <a href="/boci/backend/forms/form_account.php">Gestionar mi cuenta</a>
  <a href="/boci/backend/forms/orders.php">Mis pedidos</a>
  <a href="/boci/backend/db/db_logout.php">Log out</a>
  <button class="btnShoppingCart">
    <img src="/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
</main>

<?php
require($backend.'/footer.php');
?>