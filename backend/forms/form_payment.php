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
    <h2>Métodos de pago guardados</h2>
    <?php if (empty($payment_methods)): ?>
      <p>Todavía no tienes ningún método de pago guardado.</p>
    <?php else: ?>
      <div class="saved-payment-methods">
        <?php foreach ($payment_methods as $method): ?>
          <label class="saved-payment-option <?php echo ((int)$method['is_default'] === 1) ? 'default' : ''; ?>">
            <input
              type="radio"
              name="selected_payment_method_id"
              value="<?php echo htmlspecialchars($method['payment_method_id']); ?>"
              <?php echo ((int)$method['is_default'] === 1) ? 'checked' : ''; ?>
            >

            <div>
              <strong>
                <?php echo htmlspecialchars(strtoupper($method['method_type'])); ?>
              </strong>

              <?php if ($method['method_type'] === 'card'): ?>
                <p>
                  <?php echo htmlspecialchars($method['card_brand'] ?? 'Tarjeta'); ?>
                  terminada en
                  <?php echo htmlspecialchars($method['card_last4'] ?? '----'); ?>
                </p>

                <p>
                  Caduca:
                  <?php echo htmlspecialchars($method['exp_month']); ?>/<?php echo htmlspecialchars($method['exp_year']); ?>
                </p>
              <?php else: ?>
                <p>Google Pay</p>
              <?php endif; ?>

              <?php if ((int)$method['is_default'] === 1): ?>
                <p><strong>Predeterminado</strong></p>
              <?php endif; ?>
            </div>
          </label>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form class="payment" action="/student014/boci/backend/db/db_payment.php" method="POST" novalidate>
      <div>
        <label for="card">Número de tarjeta*</label>
        <input type="number" id="card" name="card" required />
      </div>
      <div>
        <label for="due_date">Fecha vencimiento*</label>
        <input type="date" id="due_date" name="due_date" required />
      </div>
      <div>
        <label for="cvv">CVV*</label>
        <input type="number" id="cvv" name="cvv" required />
      </div>
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