<?php
require(__DIR__.'/../header.php');
if (isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/orders.php");
  exit();
}
?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img draggable="false" src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <form class="remember" action="/student014/boci/backend/db/db_forgot_password.php" method="POST" novalidate>
    <h2>¿OLVIDÓ SU CONTRASEÑA?</h2>
    <p>Por favor, introduzca la dirección de correo electrónico que utilizó para registrarse. Recibirá un enlace temporal para restablecer su contraseña.</p>
    <div>
      <label for="email">Dirección de correo electrónico:</label>
      <input type="email" id="email" name="email" required />
    </div>
    <div class="btns-forgot">
      <button class="btn-forgot-password" type="submit">ENVIAR</button>
      <a class="btn-volver" href="/student014/boci/index.html"><p>VOLVER A INICIO</p></a>
    </div>
  </form>
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