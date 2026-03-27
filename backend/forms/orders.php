<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <a href="/boci/backend/forms/form_login.php"><img src="/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="profile">
    <div class="pages">
      <p><a href="/boci/backend/forms/form_address.php">DIRECCIÓN DE ENVÍO</a></p>
      <p><a href="/boci/backend/forms/form_payment.php">OPCIONES DE PAGO</a></p>
      <p><a href="/boci/backend/forms/form_account.php">GESTIONAR MI CUENTA</a></p>
      <p><a href="/boci/backend/forms/orders.php">MIS PEDIDOS</a></p>
    </div>
    <h2>ORDERS</h2>
  </div>
</main>

<?php
require($backend.'/footer.php');
?>