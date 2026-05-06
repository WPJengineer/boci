<?php
require(__DIR__.'/../header.php');

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

require(__DIR__ . '/../db/db_get_payments.php');

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
    <h2>Mis métodos de pago</h2>

    <div class="saved-payment-methods">
      <?php if (!empty($payment_methods)): ?>
        <?php foreach ($payment_methods as $payment): ?>

          <label class="saved-payment-option">
            <input
              type="radio"
              name="selected_payment_method_id"
              value="<?= htmlspecialchars($payment['payment_method_id']) ?>"
              <?= ((int)$payment['is_default'] === 1) ? 'checked' : '' ?>
            >

            <div class="saved-payment-content">

              <?php if ($payment['method_type'] === 'card'): ?>

                <strong class="saved-payment-title">
                  <?= htmlspecialchars($payment['card_brand'] ?? 'Tarjeta') ?>
                  terminada en <?= htmlspecialchars($payment['card_last4'] ?? '----') ?>
                </strong>

                <p>
                  Caduca:
                  <?= htmlspecialchars($payment['exp_month'] ?? '--') ?>/<?= htmlspecialchars($payment['exp_year'] ?? '----') ?>
                </p>

              <?php elseif ($payment['method_type'] === 'google_pay'): ?>

                <strong class="saved-payment-title">
                  Google Pay
                </strong>

                <p>
                  <?= !empty($payment['google_pay_email'])
                    ? htmlspecialchars($payment['google_pay_email'])
                    : 'Método de pago digital'
                  ?>
                </p>

              <?php else: ?>

                <strong class="saved-payment-title">
                  Método de pago
                </strong>

                <p>
                  <?= htmlspecialchars($payment['method_type']) ?>
                </p>

              <?php endif; ?>

              <?php if ((int)$payment['is_default'] === 1): ?>
                <p>Seleccionado</p>
              <?php endif; ?>

            </div>

            <button class="btnDeletePayment" type="button">
              <img
                class="icon remove"
                src="/student014/boci/assets/icons/close-icon-yellow.svg"
                alt="remove-icon"
              >
            </button>
          </label>

        <?php endforeach; ?>
      <?php else: ?>
        <p>No tienes métodos de pago guardados todavía.</p>
      <?php endif; ?>
    </div>

    <form class="payment" action="/student014/boci/backend/db/db_payment.php" method="POST" novalidate>

      <!-- PAYMENT TYPE -->
      <div>
        <label>Método de pago*</label>
        <select name="method_type" id="method_type" required>
          <option value="">Selecciona un método</option>
          <option value="card">Tarjeta</option>
          <option value="google_pay">Google Pay</option>
        </select>
      </div>

      <!-- CARD FIELDS -->
      <div id="card-fields">

        <div>
          <label for="card_holder">Nombre del titular*</label>
          <input type="text" id="card_holder" name="card_holder">
        </div>

        <div>
          <label for="card_brand">Tipo de tarjeta*</label>
          <select id="card_brand" name="card_brand">
            <option value="">Selecciona una tarjeta</option>
            <option value="Visa">Visa</option>
            <option value="Mastercard">Mastercard</option>
            <option value="American Express">American Express</option>
          </select>
        </div>

        <div>
          <label for="card_last4">Últimos 4 dígitos*</label>
          <input 
            type="text" 
            id="card_last4" 
            name="card_last4" 
            maxlength="4" 
            pattern="[0-9]{4}"
          >
        </div>

        <div>
          <label for="exp_month">Mes de vencimiento*</label>
          <select id="exp_month" name="exp_month">
            <option value="">Mes</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
        </div>

        <div>
          <label for="exp_year">Año de vencimiento*</label>
          <select id="exp_year" name="exp_year">
            <option value="">Año</option>
            <option value="2026">2026</option>
            <option value="2027">2027</option>
            <option value="2028">2028</option>
            <option value="2029">2029</option>
            <option value="2030">2030</option>
          </select>
        </div>

      </div>

      <!-- GOOGLE PAY FIELDS -->
      <div id="googlepay-fields" style="display:none;">
        <div>
          <label for="google_pay_email">Correo asociado a Google Pay*</label>
          <input type="email" id="google_pay_email" name="google_pay_email">
        </div>
      </div>

      <!-- DEFAULT OPTION -->
      <div class="checkbox-option">
        <input type="checkbox" id="is_default" name="is_default" value="1">
        <label for="is_default">Usar como método de pago predeterminado</label>
      </div>

      <!-- SUBMIT -->
      <div>
        <button class="btn-new-payment" type="submit">CONFIRMAR</button>
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