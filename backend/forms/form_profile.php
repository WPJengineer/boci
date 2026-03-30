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
</main>

<?php
require($backend.'/footer.php');
?>