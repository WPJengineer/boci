<?php

require(__DIR__.'/../header.php');

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_checkout_guest.php");
  exit();
}

$customerId = $_SESSION['customer_id'];

require(__DIR__ . '/../config/db_config.php');

$stmtCustomer = $conn->prepare("
  SELECT customer_forename, customer_lastname, customer_email, customer_gender
  FROM boci_customers
  WHERE customer_id = ?
  LIMIT 1
");

$stmtCustomer->bind_param("i", $customerId);
$stmtCustomer->execute();
$customer = $stmtCustomer->get_result()->fetch_assoc();
$stmtCustomer->close();

$stmtAddress = $conn->prepare("
  SELECT street, number, city, state, postal_code, country
  FROM boci_address
  WHERE customer_id = ?
    AND selected = 1
  LIMIT 1
");

$stmtAddress->bind_param("i", $customerId);
$stmtAddress->execute();
$address = $stmtAddress->get_result()->fetch_assoc();
$stmtAddress->close();

$stmtPayment = $conn->prepare("
  SELECT method_type, card_num, exp_month, exp_year
  FROM boci_payment_methods
  WHERE customer_id = ?
    AND is_default = 1
    AND is_active = 1
  LIMIT 1
");

$stmtPayment->bind_param("i", $customerId);
$stmtPayment->execute();
$payment = $stmtPayment->get_result()->fetch_assoc();
$stmtPayment->close();

$hasAddress = !empty($address);
$hasPayment = !empty($payment) && !empty($payment['method_type']);

$conn->close();

?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img draggable="false" src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="pages">
    <p><a href="/student014/boci/backend/forms/form_address.php">DIRECCIÓN DE ENVÍO</a></p>
    <p><a href="/student014/boci/backend/forms/form_payment.php">OPCIONES DE PAGO</a></p>
    <p><a href="/student014/boci/backend/forms/form_account.php">GESTIONAR MI CUENTA</a></p>
    <p><a href="/student014/boci/views/cart.html">MI CARRITO</a></p>
    <p><a href="/student014/boci/backend/forms/orders.php">MIS PEDIDOS</a></p>
  </div>
  <div class="checkout">

    <form class="personal-info" novalidate>
      <h2>1. INFORMACIÓN PERSONAL</h2>
      <img class="edit-step" src="/student014/boci/assets/icons/edit-icon.svg" alt="edit-icon">
      <div>
        <p>Tratamiento</p>
        <div class="radio-group">
          <label class="radio-option">
            <input type="radio" name="gender" value="sr" <?= (($customer['customer_gender'] ?? '') === 'sr') ? 'checked' : '' ?> required>
            Sr.
          </label>

          <label class="radio-option">
            <input type="radio" name="gender" value="sra" <?= (($customer['customer_gender'] ?? '') === 'sra') ? 'checked' : '' ?>>
            Sra.
          </label>

          <label class="radio-option">
            <input type="radio" name="gender" value="non-binary" <?= (($customer['customer_gender'] ?? '') === 'non-binary') ? 'checked' : '' ?>>
            No binario
          </label>

          <label class="radio-option">
            <input type="radio" name="gender" value="prefer-not-to-say" <?= (($customer['customer_gender'] ?? '') === 'prefer-not-to-say') ? 'checked' : '' ?>>
            Prefiero no decirlo
          </label>
        </div>
      </div>

      <div>
        <label for="name">Nombre:</label>
        <input
          type="text"
          id="name"
          name="name"
          minlength="3"
          maxlength="50"
          pattern="^[A-Za-zÀ-ÿ\s'.\-]{2,80}$"
          title="El nombre solo puede contener letras, espacios, apóstrofes o guiones."
          value="<?= htmlspecialchars($customer['customer_forename'] ?? '') ?>"
          required
        />
      </div>

      <div>
        <label for="lastname">Apellidos:</label>
        <input
          type="text"
          id="lastname"
          name="lastname"
          minlength="3"
          maxlength="50"
          pattern="^[A-Za-zÀ-ÿ\s'.\-]{2,80}$"
          title="Los apellidos solo pueden contener letras, espacios, apóstrofes o guiones."
          value="<?= htmlspecialchars($customer['customer_lastname'] ?? '') ?>"
          required
        />
      </div>

      <div>
        <label for="email">Dirección de correo electrónico:</label>
        <input
          type="email"
          id="email"
          name="email"
          maxlength="100"
          value="<?= htmlspecialchars($customer['customer_email'] ?? '') ?>"
          required
        />
      </div>

      <div>
        <button class="btn-personal-info" type="submit">CONTINUAR</button>
      </div>
    </form>

    <form class="address" data-has-address="<?= $hasAddress ? 'true' : 'false' ?>" novalidate>
      <h2>2. DIRECCIONES</h2>
      <img class="edit-step" src="/student014/boci/assets/icons/edit-icon.svg" alt="edit-icon">
      <div>
        <label for="address-line1">Dirección</label>
        <input
          type="text"
          name="address-line1"
          id="address-line1"
          minlength="2"
          maxlength="100"
          value="<?= htmlspecialchars($address['street'] ?? '') ?>"
          required
        >
      </div>

      <div>
        <label for="address-line2">Información adicional (opcional)</label>
        <input
          type="text"
          name="address-line2"
          id="address-line2"
          minlength="2"
          maxlength="100"
          value="<?= htmlspecialchars($address['number'] ?? '') ?>"
        >
      </div>

      <div>
        <label for="postal_code">Zip / Código postal</label>
        <input
          type="text"
          name="postal_code"
          id="postal_code"
          minlength="4"
          maxlength="10"
          inputmode="numeric"
          value="<?= htmlspecialchars($address['postal_code'] ?? '') ?>"
          required
        >
      </div>

      <div>
        <label for="city">Ciudad</label>
        <input
          type="text"
          name="city"
          id="city"
          minlength="2"
          maxlength="80"
          value="<?= htmlspecialchars($address['city'] ?? '') ?>"
          required
        >
      </div>

      <div>
        <label for="state">Provincia / Estado</label>
        <input
          type="text"
          name="state"
          id="state"
          minlength="2"
          maxlength="80"
          value="<?= htmlspecialchars($address['state'] ?? '') ?>"
          required
        >
      </div>

      <div>
        <label for="country">País</label>
        <select name="country" id="country" required>
          <option value="">Selecciona un país</option>
          <option value="ES" <?= (($address['country'] ?? '') === 'ES') ? 'selected' : '' ?>>España</option>
          <option value="FR" <?= (($address['country'] ?? '') === 'FR') ? 'selected' : '' ?>>Francia</option>
          <option value="DE" <?= (($address['country'] ?? '') === 'DE') ? 'selected' : '' ?>>Alemania</option>
          <option value="IT" <?= (($address['country'] ?? '') === 'IT') ? 'selected' : '' ?>>Italia</option>
          <option value="PT" <?= (($address['country'] ?? '') === 'PT') ? 'selected' : '' ?>>Portugal</option>
          <option value="UK" <?= (($address['country'] ?? '') === 'UK') ? 'selected' : '' ?>>Reino Unido</option>
          <option value="US" <?= (($address['country'] ?? '') === 'US') ? 'selected' : '' ?>>Estados Unidos</option>
        </select>
      </div>

      <div>
        <label for="phone_number">Número de teléfono (opcional)</label>
        <input
          type="text"
          id="phone_number"
          name="phone_number"
          minlength="9"
          maxlength="15"
          pattern="[0-9+ ]+"
          inputmode="tel"
          title="Introduce un número de teléfono válido."
        />
      </div>

      <div>
        <label for="identification">Número de identificación</label>
        <input
          type="text"
          id="identification"
          name="identification"
          placeholder="DNI, NIE, pasaporte, etc."
          pattern="^[A-Za-z0-9\-\.\/\s]{4,20}$"
          title="Introduce un documento de identidad válido"
          required
        >
      </div>

      <div>
        <button class="btn-new-address" type="submit">CONTINUAR</button>
      </div>
    </form>

    <form class="payment" data-has-payment="<?= $hasPayment ? 'true' : 'false' ?>" novalidate>
      <h2>3. MÉTODO DE PAGO</h2>
      <img class="edit-step" src="/student014/boci/assets/icons/edit-icon.svg" alt="edit-icon">
      <div>
        <label>Método de pago</label>
        <select name="method_type" id="method_type" required>
          <option value="">Selecciona un método</option>
          <option value="card" <?= (($payment['method_type'] ?? '') === 'card') ? 'selected' : '' ?>>Tarjeta</option>
          <option value="google_pay" <?= (($payment['method_type'] ?? '') === 'google_pay') ? 'selected' : '' ?>>Google Pay</option>
        </select>
      </div>

      <div id="card-fields">
        <div>
          <label for="card_holder">Nombre del titular</label>
          <input
            type="text"
            id="card_holder"
            name="card_holder"
            minlength="2"
            maxlength="80"
            pattern="^[A-Za-zÀ-ÿ\s'.\-]{2,80}$"
            title="El nombre del titular solo puede contener letras, espacios, apóstrofes o guiones."
            value="<?= htmlspecialchars(($customer['customer_forename'] ?? '') . ' ' . ($customer['customer_lastname'] ?? '')) ?>"
            required
          >
        </div>

        <div>
          <label for="card_num">Número de tarjeta</label>
          <input
            type="text"
            id="card_num"
            name="card_num"
            minlength="16"
            maxlength="16"
            pattern="[0-9]{16}"
            inputmode="numeric"
            title="Introduce el número de la tarjeta."
            value="<?= (($payment['method_type'] ?? '') === 'card' && !empty($payment['card_num']))
              ? '************' . htmlspecialchars(substr($payment['card_num'], -4))
              : '' ?>"
            <?= (($payment['method_type'] ?? '') === 'card' && !empty($payment['card_num']))
              ? 'readonly'
              : 'required' ?>
          >
        </div>

        <div>
          <label for="exp_month">Mes de vencimiento</label>
          <select id="exp_month" name="exp_month" required>
            <option value="">Mes</option>
            <option value="01" <?= (($payment['exp_month'] ?? '') == '1' || ($payment['exp_month'] ?? '') == '01') ? 'selected' : '' ?>>01</option>
            <option value="02" <?= (($payment['exp_month'] ?? '') == '2' || ($payment['exp_month'] ?? '') == '02') ? 'selected' : '' ?>>02</option>
            <option value="03" <?= (($payment['exp_month'] ?? '') == '3' || ($payment['exp_month'] ?? '') == '03') ? 'selected' : '' ?>>03</option>
            <option value="04" <?= (($payment['exp_month'] ?? '') == '4' || ($payment['exp_month'] ?? '') == '04') ? 'selected' : '' ?>>04</option>
            <option value="05" <?= (($payment['exp_month'] ?? '') == '5' || ($payment['exp_month'] ?? '') == '05') ? 'selected' : '' ?>>05</option>
            <option value="06" <?= (($payment['exp_month'] ?? '') == '6' || ($payment['exp_month'] ?? '') == '06') ? 'selected' : '' ?>>06</option>
            <option value="07" <?= (($payment['exp_month'] ?? '') == '7' || ($payment['exp_month'] ?? '') == '07') ? 'selected' : '' ?>>07</option>
            <option value="08" <?= (($payment['exp_month'] ?? '') == '8' || ($payment['exp_month'] ?? '') == '08') ? 'selected' : '' ?>>08</option>
            <option value="09" <?= (($payment['exp_month'] ?? '') == '9' || ($payment['exp_month'] ?? '') == '09') ? 'selected' : '' ?>>09</option>
            <option value="10" <?= (($payment['exp_month'] ?? '') == '10') ? 'selected' : '' ?>>10</option>
            <option value="11" <?= (($payment['exp_month'] ?? '') == '11') ? 'selected' : '' ?>>11</option>
            <option value="12" <?= (($payment['exp_month'] ?? '') == '12') ? 'selected' : '' ?>>12</option>
          </select>
        </div>

        <div>
          <label for="exp_year">Año de vencimiento</label>
          <select id="exp_year" name="exp_year" required>
            <option value="">Año</option>
            <option value="2026" <?= (($payment['exp_year'] ?? '') == '2026') ? 'selected' : '' ?>>2026</option>
            <option value="2027" <?= (($payment['exp_year'] ?? '') == '2027') ? 'selected' : '' ?>>2027</option>
            <option value="2028" <?= (($payment['exp_year'] ?? '') == '2028') ? 'selected' : '' ?>>2028</option>
            <option value="2029" <?= (($payment['exp_year'] ?? '') == '2029') ? 'selected' : '' ?>>2029</option>
            <option value="2030" <?= (($payment['exp_year'] ?? '') == '2030') ? 'selected' : '' ?>>2030</option>
          </select>
        </div>
      </div>

      <div id="googlepay-fields" style="display:none;">
        <div>
          <label for="google_pay_email">Correo asociado a Google Pay*</label>
          <input
            type="email"
            id="google_pay_email"
            name="google_pay_email"
            maxlength="100"
            value="<?= (($payment['method_type'] ?? '') === 'google_pay') ? htmlspecialchars($customer['customer_email'] ?? '') : '' ?>"
          >
        </div>
      </div>

      <div>
        <button class="btn-new-payment" type="submit">CONTINUAR</button>
      </div>
    </form>

    <form class="place-order is-collapsed" id="registered-place-order">
      <h2>4. CONFIRMAR PEDIDO</h2>
      <section id="checkout-products" class="orders-container"></section>
      <div>
        <button class="btn-place-order" type="submit">REALIZAR PEDIDO</button>
      </div>
    </form>

  </div>

  <button class="btnShoppingCart">
    <img draggable="false" src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
    <span id="counter">0</span>
  </button>

  <button type="button" class="btnLogOut">
    <img draggable="false" class="icon" src="/student014/boci/assets/icons/logout-icon-black.svg" alt="log-out-icon">
  </button>
</main>

<?php
require(__DIR__.'/../footer.php');
?>