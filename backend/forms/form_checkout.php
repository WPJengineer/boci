<?php
session_start();

// if (!isset($_SESSION['customer_id'])) {
//     header("Location: /student014/boci/backend/forms/form_login.php?redirect=/student014/boci/backend/forms/form_checkout.php");
//     exit();
// }

// $customerId = $_SESSION['customer_id'];

require(__DIR__.'/../header.php');
?>

<main>
  <a href="/student014/boci/backend/forms/form_login.php"><img draggable="false" src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <div class="checkout">
    <form class="personal-info" novalidate>
        <h2>1. INFORMACIÓN PERSONAL</h2>
        <!-- this shouldnt be visible if they are already logged in -->
        <?php if (!isset($_SESSION['customer_id'])): ?>
            <div class="login">
                <a href="/student014/boci/backend/forms/form_register.php">Crear una cuenta</a>
                <a href="/student014/boci/backend/forms/form_login.php">Acceder a cuenta</a>
            </div>
        <?php endif; ?>
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
                <label class="radio-option">
                    <input type="radio" name="gender" value="non-binary">
                    No binario
                </label>
                <label class="radio-option">
                    <input type="radio" name="gender" value="prefer-not-to-say">
                    Prefiero no decirlo
                </label>
            </div>
        </div>
        <div>
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" minlength="3" maxlength="50" pattern="^[A-Za-zÀ-ÿ\s'.\-]{2,80}$" title="El nombre solo puede contener letras, espacios, apóstrofes o guiones." required />
        </div>
        <div>
            <label for="lastname">Apellidos:</label>
            <input type="text" id="lastname" name="lastname" minlength="3" maxlength="50" pattern="^[A-Za-zÀ-ÿ\s'.\-]{2,80}$" title="Los apellidos solo pueden contener letras, espacios, apóstrofes o guiones." required />
        </div>
        <div>
            <label for="email">Dirección de correo electrónico:</label>
            <input type="email" id="email" name="email" maxlength="100" required />
        </div>
        <div>
            <button class="btn-personal-info" type="submit">CONTINUAR</button>
        </div>
    </form>
    <form class="address" novalidate>
        <h2>2. DIRECCIONES</h2>
        <div>
            <label for="address-line1">Dirección</label>
            <input type="text" name="address-line1" id="address-line1" minlength="2" maxlength="100" required>
        </div>
        <div>
            <label for="address-line2">Información adicional (opcional)</label>
            <input type="text" name="address-line2" id="address-line2" minlength="2" maxlength="100">
        </div>
        <div>
            <label for="postal_code">Zip / Código postal</label>
            <input type="text" name="postal_code" id="postal_code" minlength="4" maxlength="10" inputmode="numeric" required>
        </div>
        <div>
            <label for="city">Ciudad</label>
            <input type="text" name="city" id="city" minlength="2" maxlength="80" required>
        </div>
        <div>
            <label for="state">Provincia / Estado</label>
            <input type="text" name="state" id="state" minlength="2" maxlength="80">
        </div>
        <div>
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
        </div>
        <div>
            <label for="phone_number">Número de teléfono (opcional)</label>
            <input type="text" id="phone_number" name="phone_number" minlength="9" maxlength="15" pattern="[0-9+ ]+" inputmode="tel" title="Introduce un número de teléfono válido." />
        </div>
        <div>
            <label for="identification">Número de identificación</label>
            <input type="text" id="identification" name="identification" placeholder="DNI, NIE, pasaporte, etc." pattern="^[A-Za-z0-9\-\.\/\s]{4,20}$" title="Introduce un documento de identidad válido" required>
        </div>
        <div>
            <button class="btn-new-address" type="submit">CONTINUAR</button>
        </div>
    </form>
    <form class="payment" novalidate>
        <h2>3. MÉTODO DE PAGO</h2>
        <div>
            <label>Método de pago</label>
            <select name="method_type" id="method_type" required>
                <option value="">Selecciona un método</option>
                <option value="card">Tarjeta</option>
                <option value="google_pay">Google Pay</option>
            </select>
        </div>
        <div id="card-fields">
            <div>
                <label for="card_holder">Nombre del titular</label>
                <input type="text" id="card_holder" name="card_holder" minlength="2" maxlength="80" pattern="^[A-Za-zÀ-ÿ\s'.\-]{2,80}$" title="El nombre del titular solo puede contener letras, espacios, apóstrofes o guiones." required>
            </div>
            <!-- <div>
                <label for="card_brand">Tipo de tarjeta*</label>
                <select id="card_brand" name="card_brand" required>
                <option value="">Selecciona una tarjeta</option>
                <option value="Visa">Visa</option>
                <option value="Mastercard">Mastercard</option>
                <option value="American Express">American Express</option>
                </select>
            </div> -->
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
                required
                >
            </div>
            <div>
                <label for="exp_month">Mes de vencimiento</label>
                <select id="exp_month" name="exp_month" required>
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
                <label for="exp_year">Año de vencimiento</label>
                <select id="exp_year" name="exp_year" required>
                <option value="">Año</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2029</option>
                <option value="2030">2030</option>
                </select>
            </div>
        </div>
        <div id="googlepay-fields" style="display:none;">
            <div>
                <label for="google_pay_email">Correo asociado a Google Pay*</label>
                <input type="email" id="google_pay_email" name="google_pay_email" maxlength="100">
            </div>
        </div>
        <div>
            <button class="btn-new-payment" type="submit">CONTINUAR</button>
        </div>
    </form>
    <!-- each form needs to be auto filled with customer data if logged in or used as temporary values if done as a guest -->
    <!-- missing to add green border to form that is correctly submitted and also icon to allow edit -->
    <!-- need to show items in shopping cart we are purchasing-->
    <form class="place-order is-collapsed">
        <h2>4. CONFIRMAR PEDIDO</h2>
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