<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <a href="/boci/backend/forms/form_login.php"><img src="/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <form class="new-register" action="/boci/backend/db/db_register.php" method="POST" novalidate>
    <h2>CREA UNA CUENTA</h2>
    <p>¿Ya tiene una cuenta? <a href="/boci/backend/forms/form_login.php">INICIE SESIÓN</a></p>
    <div>
      <p>Tratamiento</p>
      <div class="radio-group">
        <label class="radio-option">
          <input type="radio" name="gender" value="sr" required>
          Sr.
        </label>
        <label class="radio-option">
          <input type="radio" name="gender" value="sra">
          Sra.
        </label>
      </div>
    </div>
    <div>
      <label for="name">Nombre:</label>
      <input type="text" id="name" name="name" required />
    </div>
    <div>
      <label for="lastname">Apellidos:</label>
      <input type="text" id="lastname" name="lastname" required />
    </div>
    <div>
      <label for="email">Dirección de correo electrónico:</label>
      <input type="email" id="email" name="email" required />
    </div>
    <div>
      <label for="password">Contraseña:</label>
      <div class="password-field">
        <input type="password" id="password" name="password" required />
        <button type="button" class="btn-show-password">
          <img src="/boci/assets/icons/show-password-icon.svg" alt="show-password-icon">
        </button>
      </div>
    </div>
    <div class="privacy-selection">
      <p>Privacidad de los datos del cliente</p>
      <label for="privacy" class="checkbox-option">
        <input type="checkbox" id="privacy" name="privacy" required>Los datos personales que proporciona son utilizados para satisfacer sus necesidades, procesar pedidos o permitirle el acceso a una información específica. Usted tiene el derecho de modificar y eliminar toda la información personal que se encuentra en la página "Mi Cuenta".
      </label>
      <label for="newsletter" class="checkbox-option">
        <input type="checkbox" id="newsletter" name="newsletter" checked>Suscríbase a nuestro newsletter. Puede darse de baja en cualquier momento. Para ello, consulte nuestra información de contacto en el aviso legal.
      </label>
    </div>
    <div>
      <button class="btn-new-register" type="submit">GUARDAR</button>
    </div>
  </form>
</main>

<?php
require($backend.'/footer.php');
?>