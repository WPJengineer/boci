<?php
require(__DIR__.'/../header.php');

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

require(__DIR__ . '/../endpoints/get_account_details.php');

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
    <form class="account-email" action="/student014/boci/backend/db/db_account.php" method="POST" novalidate>
      <div>
        <label for="email">Correo electrónico*</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($details['customer_email'] ?? '') ?>" maxlength="100" required />
      </div>
      <div>
        <button class="btn-new-email" type="submit">CAMBIAR</button>
      </div>
    </form>
    <form class="account-phone" action="/student014/boci/backend/db/db_account.php" method="POST" novalidate>
      <div>
        <label for="phone_number">Número de teléfono*</label>
        <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($details['customer_phone'] ?? '') ?>" minlength="9" maxlength="15" pattern="[0-9+ ]+" inputmode="tel" title="Introduce un número de teléfono válido." required />
      </div>
      <div>
        <button class="btn-new-phone" type="submit">CAMBIAR</button>
      </div>
    </form>
    <form class="account-password" action="/student014/boci/backend/db/db_account.php" method="POST" novalidate>
      <div>
        <label for="password">Nueva contraseña*</label>
        <input type="password" id="password" name="password" minlength="8" maxlength="72" required />
      </div>
      <div>
        <button class="btn-new-password" type="submit">CAMBIAR</button>
      </div>
    </form>
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