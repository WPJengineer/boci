<?php
require(__DIR__.'/../header.php');

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

include('../db/db_get_addresses.php');

?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="profile">
    <div class="pages">
      <p><a href="/student014/boci/backend/forms/form_address.php">DIRECCIÓN DE ENVÍO</a></p>
      <p><a href="/student014/boci/backend/forms/form_payment.php">OPCIONES DE PAGO</a></p>
      <p><a href="/student014/boci/backend/forms/form_account.php">GESTIONAR MI CUENTA</a></p>
      <p><a href="/student014/boci/views/cart.html">MI CARRITO</a></p>
      <p><a href="/student014/boci/backend/forms/orders.php">MIS PEDIDOS</a></p>
    </div>
    <h2>Mis direcciones</h2>
    <div class="saved-addresses">
      <?php if (!empty($addresses)): ?>
        <?php foreach ($addresses as $address): ?>
          <label class="saved-address-option">
            <input
              type="radio"
              name="selected_address_id"
              value="<?= htmlspecialchars($address['address_id']) ?>"
              <?= $address['selected'] ? 'checked' : '' ?>
            >
            <span>
              <strong>
                <?= htmlspecialchars($address['street']) ?>
                <?= htmlspecialchars($address['number']) ?>
              </strong>
              <br>
              <?= htmlspecialchars($address['postal_code']) ?>
              <?= htmlspecialchars($address['city']) ?>,
              <?= htmlspecialchars($address['state']) ?>
              <br>
              <?= htmlspecialchars($address['country']) ?>
            </span>
          </label>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No tienes direcciones guardadas todavía.</p>
      <?php endif; ?>
    </div>
    <h2>Añadir nueva dirección</h2>
    <form class="address" action="/student014/boci/backend/db/db_address.php" method="POST" novalidate>
      <label for="country">País</label>
      <select name="country" id="country" required>
        <option value="">Selecciona un país</option>
        <option value="ES">España</option>
        <option value="FR">Francia</option>
        <option value="DE">Alemania</option>
        <option value="IT">Italia</option>
        <option value="PT">Portugal</option>
        <option value="UK">Reino Unido</option>
        <option value="US">Estados Unidos</option>
      </select>
      <label for="state">Provincia / Estado</label>
      <input type="text" name="state" id="state" required>
      <label for="city">Ciudad</label>
      <input type="text" name="city" id="city" required>
      <label for="postal_code">Código postal</label>
      <input type="text" name="postal_code" id="postal_code" required>
      <label for="street_name">Calle</label>
      <input type="text" name="street_name" id="street_name" required>
      <label for="street_num">Número</label>
      <input type="text" name="street_num" id="street_num">
      <div>
        <button class="btn-new-address" type="submit">GUARDAR</button>
      </div>
    </form>
  </div>
  <button class="btnShoppingCart">
    <img src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>
  <button class="btnLogOut">
    <img class="icon" src="/student014/boci/assets/icons/logout-icon-black.svg" alt="log-out-icon">
  </button>
</main>

<?php
require(__DIR__.'/../footer.php');
?>