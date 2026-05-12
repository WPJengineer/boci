<?php
require(__DIR__.'/../header.php');
$redirect = $_GET['redirect'] ?? '';
if (isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/orders.php");
  exit();
}
?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img draggable="false" src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <form class="new-register" action="/student014/boci/backend/db/db_register.php" method="POST" novalidate>
    <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
    <h2>CREA UNA CUENTA</h2>
    <p>¿Ya tiene una cuenta? <a href="/student014/boci/backend/forms/form_login.php">INICIE SESIÓN</a></p>
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
      <input type="text" id="name" name="name" minlength="3" maxlength="50" pattern="[A-Za-zÀ-ÿ\s'-]+" title="El nombre solo puede contener letras, espacios, apóstrofes o guiones." required />
    </div>
    <div>
      <label for="lastname">Apellidos:</label>
      <input type="text" id="lastname" name="lastname" minlength="3" maxlength="50" pattern="[A-Za-zÀ-ÿ\s'-]+" title="Los apellidos solo pueden contener letras, espacios, apóstrofes o guiones." required />
    </div>
    <div>
      <label for="email">Dirección de correo electrónico:</label>
      <input type="email" id="email" name="email" maxlength="100" required />
    </div>
    <div>
      <label for="password">Contraseña:</label>
      <div class="password-field">
        <input type="password" id="password" name="password" minlength="8" maxlength="72" required />
        <button type="button" class="btn-show-password">
          <img draggable="false" src="/student014/boci/assets/icons/show-password-icon.svg" alt="show-password-icon">
        </button>
      </div>
    </div>
    <div>
      <label for="confirm-password">Confirmar contraseña:</label>
      <div class="confirm-password-field">
        <input type="password" id="confirm-password" name="confirm-password" minlength="8" maxlength="72" required />
        <button type="button" class="btn-show-confirm-password">
          <img draggable="false" src="/student014/boci/assets/icons/show-password-icon.svg" alt="show-password-icon">
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
  <button class="btnShoppingCart">
    <img draggable="false" src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
  <!-- <button type="button" class="btnLogOut">
    <img draggable="false" class="icon" src="/student014/boci/assets/icons/logout-icon-black.svg" alt="log-out-icon">
  </button> -->
</main>

<?php
require(__DIR__.'/../footer.php');
?>