<?php
require(__DIR__.'/../header.php');
?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="profile">
    <div class="pages">
      <p><a href="/student014/boci/backend/forms/form_address.php">DIRECCIÓN DE ENVÍO</a></p>
      <p><a href="/student014/boci/backend/forms/form_payment.php">OPCIONES DE PAGO</a></p>
      <p><a href="/student014/boci/backend/forms/form_account.php">GESTIONAR MI CUENTA</a></p>
      <p><a href="/student014/boci/backend/forms/orders.php">MIS PEDIDOS</a></p>
    </div>
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
</main>

<?php
require(__DIR__.'/../footer.php');
?>