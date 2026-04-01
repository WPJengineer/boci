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
    <form class="account-email" action="/boci/backend/db/db_account.php" method="POST" novalidate>
      <div>
        <label for="email">Correo electrónico*</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div>
        <button class="btn-new-email" type="submit">CAMBIAR</button>
      </div>
    </form>
    <form class="account-phone" action="/boci/backend/db/db_account.php" method="POST" novalidate>
      <div>
        <label for="phone_number">Número de teléfono*</label>
        <input type="number" id="phone_number" name="phone_number" required />
      </div>
      <div>
        <button class="btn-new-phone" type="submit">CAMBIAR</button>
      </div>
    </form>
  </div>
  <button class="btnShoppingCart">
    <img src="/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
</main>

<?php
require($backend.'/footer.php');
?>