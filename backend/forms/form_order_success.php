<?php

require(__DIR__.'/../header.php');

// show successful purchase with details and then also send email of receipt (inform them that an email containing details of purchase has been sent to their email).
// buttons to:
// seguir comprando
// mis pedidos

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

  <div class="order-confirm">
    <img class="success" src="/student014/boci/assets/images/order_success_image.png" alt="order-success-image">
    <h1>¡Pedido confirmado!</h1>
    <p>Tu pedido se ha realizado correctamente.</p>
    <div class="order-num">
      <p>Número de pedido</p>
      <span></span>
    </div>
    <div>
      <img src="" alt="email-icon">
      <p>Te hemos enviado un correo electrónico con el recibo y los detalles de tu pedido.</p>
    </div>
    <div class="order-details">
      <p>Resumen de pedido</p>
      <div class="order-date">
        <img src="" alt="">
        <p>Fecha</p>
        <span></span>
      </div>
      <div class="order-num">
        <img src="" alt="">
        <p>Número de pedido</p>
        <span></span>
      </div>
      <div class="order-payment">
        <img src="" alt="">
        <p>Método de pago</p>
        <span></span>
      </div>
      <div class="order-address">
        <img src="" alt="">
        <p>Dirección de envío</p>
        <span></span>
      </div>
      <div class="order-subtotal">
        <p>Total pagado</p>
        <span></span>
      </div>
    </div>
    <p>Prepararemos tu pedido lo antes posible. Puedes consultar el estado del pedido en <a href="/student014/boci/backend/forms/orders.php">Mis pedidos</a>.</p>
    <div class="order-buttons">
      <button>VER MIS PEDIDOS</button>
      <button>SEGUIR COMPRANDO</button>
    </div>
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