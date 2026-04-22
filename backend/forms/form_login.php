<?php
require(__DIR__ . '/../header.php');
$redirect = $_GET['redirect'] ?? '/student014/boci/backend/forms/form_profile.php';

if (isset($_SESSION['customer_id'])) {
    header("Location: /student014/boci/backend/forms/form_profile.php");
    exit();
}
?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="forms-login">
    <form class="register" action="/student014/boci/backend/forms/form_register.php">
      <div>
        <h2>¿NUEVOS CLIENTES?</h2>
        <p>Es rápido y fácil crear una cuenta para comprar más rápido y gurdar su pedido en la cuenta.</p>
      </div>
      <button type="submit" class="btn-register">
        <img class="icon" src="/student014/boci/assets/icons/profile-icon-white.svg" alt="profile-icon">
        CREA UNA CUENTA
      </button>
    </form>
    <form class="login" action="/student014/boci/backend/db/db_login.php" method="POST" novalidate>
      <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
      <input type="hidden" name="cart_data" id="cart_data">
      <h2>CLIENTES REGISTRADOS</h2>
      <div>
        <label for="email">Dirección de correo electrónico:</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div>
        <label for="password">Contraseña:</label>
        <div class="password-field">
          <input type="password" id="password" name="password" required />
          <button type="button" class="btn-show-password">
            <img src="/student014/boci/assets/icons/show-password-icon.svg" alt="show-password-icon">
          </button>
        </div>
      </div>
      <div>
        <!-- missing page -->
        <a href="/student014/boci/backend/forms/form_forgot_password.php">¿Olvidó su contraseña?</a>
      </div>
      <div>
        <button type="submit" class="btn-login">
          <img class="icon" src="/student014/boci/assets/icons/padlock-white-icon.svg" alt="padlock-icon">
          INICIA SESIÓN
        </button>
      </div>
    </form>
  </div>
  <button class="btnShoppingCart">
    <img src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
</main>

<?php
require(__DIR__ . '/../footer.php');
?>