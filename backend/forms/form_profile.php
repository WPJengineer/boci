<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <button>Dirección de envíos</button>
  <button>Opciones de pago</button>
  <button>Gestionar mi cuenta</button>
  <button>Mis pedidos</button>
  <button>Log out</button>
</main>

<?php
require($backend.'/footer.php');
?>