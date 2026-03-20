<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <div class="forms-login">
    <form class="register" action="/boci/backend/forms/form_register.php">
      <div>
        <h2>¿NUEVOS CLIENTES?</h2>
        <p>Es rápido y fácil crear una cuenta para comprar más rápido y gurdar su pedido en la cuenta.</p>
      </div>
      <button type="submit" class="btn-register">
        <img class="icon" src="/boci/assets/icons/profile-icon-white.svg" alt="profile-icon">
        CREA UNA CUENTA
      </button>
    </form>
    <form class="login" action="/boci/backend/db/db_login.php" method="POST">
      <h2>CLIENTES REGISTRADOS</h2>
      <p>
        <label for="email">Dirección de correo electrónico:</label>
        <input type="email" id="email" name="email" />
      </p>
      <p>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" />
      </p>
      <p>
        <a href="">¿Olvidó su contraseña?</a>
      </p>
      <p>
        <button type="submit" class="btn-login">
          <img class="icon" src="/boci/assets/icons/padlock-white-icon.svg" alt="padlock-icon">
          INICIA SESIÓN
      </button>
      </p>
    </form>
  </div>
</main>

<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/footer.php');
?>