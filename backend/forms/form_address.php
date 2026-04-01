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
    <form class="address" action="/boci/backend/db/db_address.php" method="POST" novalidate>
      <div>
        <label for="name">Nombre*</label>
        <input type="text" id="name" name="name" required />
      </div>
      <div>
        <label for="lastname">Apellidos*</label>
        <input type="text" id="lastname" name="lastname" required />
      </div>
      <div>
        <label for="phone_number">Número de teléfono*</label>
        <input type="text" id="phone_number" name="phone_number" required />
      </div>
      <div>
        <label for="street_name">Nombre de la calle*</label>
        <input type="text" id="street_name" name="street_name" required />
      </div>
      <div>
        <label for="street_num">Número del local*</label>
        <input type="text" id="street_num" name="street_num" required />
      </div>
      <div>
        <label for="city">Ciudad*</label>
        <input type="text" id="city" name="city" required />
      </div>
      <div>
        <label for="post_code">Código postal*</label>
        <input type="text" id="post_code" name="post_code" required />
      </div>
      <div>
        <label for="state">Estado/Provincia*</label>
        <input type="text" id="state" name="state" required />
      </div>
      <div>
        <button class="btn-new-address" type="submit">GUARDAR</button>
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