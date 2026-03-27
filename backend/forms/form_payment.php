<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <a href="/boci/backend/forms/form_login.php"><img src="/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="profile">
    <div class="pages">
      <p><a href="/boci/backend/forms/form_address.php">DIRECCIÓN DE ENVÍO</a></p>
      <p><a href="/boci/backend/forms/form_payment.php">OPCIONES DE PAGO</a></p>
      <p><a href="/boci/backend/forms/form_account.php">GESTIONAR MI CUENTA</a></p>
      <p><a href="/boci/backend/forms/orders.php">MIS PEDIDOS</a></p>
    </div>
    <form class="payment" action="/boci/backend/db/db_payment.php" method="POST" novalidate>
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
</main>

<?php
require($backend.'/footer.php');
?>