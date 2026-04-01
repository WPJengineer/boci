<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <a href="/boci/backend/forms/form_login.php"><img src="/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <form class="remember" action="/boci/backend/db/db_forgot_password.php" method="POST" novalidate>
    <h2>¿OLVIDÓ SU CONTRASEÑA?</h2>
    <p>Por favor, introduzca la dirección de correo electrónico que utilizó para registrarse. Recibirá un enlace temporal para restablecer su contraseña.</p>
    <div>
      <label for="email">Dirección de correo electrónico:</label>
      <input type="email" id="email" name="email" required />
    </div>
    <div class="btns-forgot">
      <button class="btn-forgot-password" type="submit">ENVIAR</button>
      <a class="btn-volver" href="/boci/index.html"><p>VOLVER A INICIO</p></a>
    </div>
  </form>
  <button class="btnShoppingCart">
    <img src="/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
</main>

<?php
require($backend.'/footer.php');
?>