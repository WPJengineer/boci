const password = document.querySelector(".password-field input");
const showPassword = document.querySelector(".btn-show-password");
const confirmPassword = document.querySelector(".confirm-password-field input");
const showConfirmPassword = document.querySelector(".btn-show-confirm-password");
const btnShoppingCart = document.querySelector(".btnShoppingCart");
const counterCart = document.getElementById("counter");
const cartParams = new URLSearchParams(window.location.search);
const errorMessages = {
  admin_required: "No tienes permisos de administrador para acceder a esta página.",
  login_required: "Debes iniciar sesión para acceder a esta página."
};
const errorCode = cartParams.get("error");
if (errorCode && errorMessages[errorCode]) {
  showMessage(errorMessages[errorCode], "error");

  const cleanUrl = window.location.pathname;
  window.history.replaceState({}, document.title, cleanUrl);
}
const editButtons = document.querySelectorAll(".admin-buttons .edit");
const deleteButtons = document.querySelectorAll(".admin-buttons .delete");
const btnLogOut = document.querySelector(".btnLogOut");
const message = document.querySelector(".message");
const btnDeleteAddress = document.querySelectorAll(".btnDeleteAddress");
const btnDeletePayment = document.querySelectorAll(".btnDeletePayment");
const btnViewOrders = document.querySelector(".view");
const btnKeepShopping = document.querySelector(".follow");
const articles = document.querySelectorAll("article");

if (cartParams.get("clearCart") === "1") {
  localStorage.removeItem("cart");
}

function clearValidation(input) {
  input.classList.remove("valid", "invalid");
}

function animateValidation(input, type, duration = 3000) {
  if (!input) return;
  const opposite = type === "valid" ? "invalid" : "valid";
  input.classList.remove(opposite);
  input.classList.add(type);
  setTimeout(() => {
    input.classList.remove(type);
  }, duration);
}

function getValidationMessage(input) {
  if (input.validity.valueMissing) {
    return "Este campo es obligatorio.";
  }

  if (input.validity.typeMismatch) {
    if (input.type === "email") {
      return "Introduce un email válido.";
    }

    return "El formato introducido no es válido.";
  }

  if (input.validity.patternMismatch) {
    return input.title || "El formato introducido no es válido.";
  }

  if (input.validity.tooShort) {
    return `Debe tener al menos ${input.minLength} caracteres.`;
  }

  if (input.validity.tooLong) {
    return `Debe tener como máximo ${input.maxLength} caracteres.`;
  }

  if (input.validity.rangeUnderflow) {
    return `El valor debe ser como mínimo ${input.min}.`;
  }

  if (input.validity.rangeOverflow) {
    return `El valor debe ser como máximo ${input.max}.`;
  }

  if (input.validity.stepMismatch) {
    return "Introduce un valor válido.";
  }

  return "Revisa este campo.";
}

function validateInput(input, allowEmptyValid = false) {
  if (input.checkValidity()) {
    clearValidation(input);
    if (allowEmptyValid || input.value.trim() !== "") {
      animateValidation(input, "valid");
    }
    return true;
  }
  clearValidation(input);
  animateValidation(input, "invalid");
  return false;
}

function validateFormInputs(inputs, ignoredNames = [], allowEmptyValid = true) {
  let firstInvalidInput = null;

  inputs.forEach((input) => {
    if (ignoredNames.includes(input.name)) return;

    const isValid = validateInput(input, allowEmptyValid);

    if (!isValid && !firstInvalidInput) {
      firstInvalidInput = input;
    }
  });

  if (firstInvalidInput) {
    showMessage(getValidationMessage(firstInvalidInput), "error");
    firstInvalidInput.focus();
    return false;
  }

  return true;
}

function animateMessage(messageElement) {
  if (!messageElement) return;

  setTimeout(() => {
    messageElement.classList.add("show");
  }, 50);

  setTimeout(() => {
    messageElement.classList.remove("show");
    setTimeout(() => messageElement.remove(), 300);
  }, 3000);
}

function showMessage(text, type = "success") {
  const oldMessage = document.querySelector(".message");
  if (oldMessage) oldMessage.remove();

  const newMessage = document.createElement("div");
  newMessage.className = `message ${type}`;
  newMessage.textContent = text;

  document.body.appendChild(newMessage);
  animateMessage(newMessage);
}

animateMessage(message);

if (articles) {
  articles.forEach(article => {
    article.addEventListener("click", () => {
      window.location.href = `/student014/boci/backend/admin/product.php?id=${article.dataset.id}`;
    });
  });
}

editButtons.forEach(button => {
  button.addEventListener("click", e => {
    e.stopPropagation();

    console.log("edit");
    // edit code here
  });
});

deleteButtons.forEach(button => {
  button.addEventListener("click", e => {
    e.stopPropagation();

    console.log("delete");
    // delete code here
  });
});

async function getCart() {
  try {
    const user = await getSessionUser();
    if (user?.loggedIn) {
      const response = await fetch(
        // `http://localhost/boci/backend/endpoints/cart_frontend.php`,
        `https://remotehost.es/student014/boci/backend/endpoints/cart_frontend.php`,
        {credentials: "include"});
      
      if (!response.ok) {
        throw new Error("Could not fetch cart from backend");
      }

      const products = await response.json();
      return Array.isArray(products) ? products : [];
    }
    const raw = localStorage.getItem("cart");
    const cart = raw ? JSON.parse(raw) : [];
    return Array.isArray(cart) ? cart : [];
  } catch {
    return [];
  }
}

async function getCartCount() {
  const cart = await getCart();
  return cart.reduce((total, item) => total + Number(item.quantity || 0), 0);
}

async function updateCartBadge() {
  if (!counterCart) return;
  counterCart.textContent = await getCartCount();
}

updateCartBadge();

if (btnShoppingCart) {
  btnShoppingCart.addEventListener('click', () => {
    window.location.href = "/student014/boci/views/cart.html";
  })
};

if (password && showPassword) {
  showPassword.addEventListener("pointerdown", () => {
    password.type = "text";
  });

  showPassword.addEventListener("pointerup", () => {
    password.type = "password";
  });
}

if (confirmPassword && showConfirmPassword) {
  showConfirmPassword.addEventListener("pointerdown", () => {
    confirmPassword.type = "text";
  });

  showConfirmPassword.addEventListener("pointerup", () => {
    confirmPassword.type = "password";
  });
}

// missing to check for if valid entry into form

const formLogin = document.querySelector(".login");
const formNewRegister = document.querySelector(".new-register");

const checkout = document.querySelector(".checkout");

const formPersonalInfo = checkout?.querySelector(".personal-info");
const formCheckoutAddress = checkout?.querySelector(".address");
const formCheckoutPayment = checkout?.querySelector(".payment");
const formPlaceOrder = checkout?.querySelector(".place-order");
const guestPlaceOrder = document.getElementById("guest-place-order");

const formNewAddress = document.querySelector(".profile .address");
const formNewPayment = document.querySelector(".profile .payment");
const formNewEmail = document.querySelector(".account-email");
const formNewPhone = document.querySelector(".account-phone");
const formNewPassword = document.querySelector(".account-password");
const links = document.querySelectorAll('.pages p a');
const methodSelect = document.getElementById("method_type");
const cardFields = document.getElementById("card-fields");
const googlePayFields = document.getElementById("googlepay-fields");
const checkoutForms = [
  formPersonalInfo,
  formCheckoutAddress,
  formCheckoutPayment,
  formPlaceOrder
].filter(Boolean);

function openCheckoutStep(stepIndex) {
  checkoutForms.forEach((form, index) => {
    form.classList.toggle("is-collapsed", index !== stepIndex);
    form.classList.toggle("is-active", index === stepIndex);
  });
}

const checkoutStepButtons = checkout?.querySelectorAll(".edit-step");

if (checkoutStepButtons && checkoutStepButtons.length > 0) {
  checkoutStepButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const selectedForm = button.closest("form");
      const selectedIndex = checkoutForms.indexOf(selectedForm);

      if (selectedIndex !== -1) {
        openCheckoutStep(selectedIndex);
      }
    });
  });
}

if (checkoutForms.length > 0) {
  openCheckoutStep(0);
}

if (formLogin) {
  const inputsLogin = formLogin.querySelectorAll("input");
  const cartInput = document.getElementById("cart_data");

  formLogin.addEventListener("submit", (e) => {
    if (cartInput) {
      cartInput.value = localStorage.getItem("cart") || "[]";
    }

    if (!validateFormInputs(inputsLogin)) {
      e.preventDefault();
    }
  });
}

if (formNewRegister) {
  const inputsRegister = formNewRegister.querySelectorAll("input");
  const passwordInput = formNewRegister.querySelector("#password");
  const confirmPasswordInput = formNewRegister.querySelector("#confirm-password");

  formNewRegister.addEventListener("submit", (e) => {
    if (!validateFormInputs(inputsRegister, ["newsletter"])) {
      e.preventDefault();
      return;
    }

    if (
      passwordInput &&
      confirmPasswordInput &&
      passwordInput.value !== confirmPasswordInput.value
    ) {
      e.preventDefault();

      animateValidation(passwordInput, "invalid");
      animateValidation(confirmPasswordInput, "invalid");

      showMessage("Las contraseñas no coinciden.", "error");
      confirmPasswordInput.focus();
    }
  });
}

if (formPersonalInfo) {
  const inputsPersonalInfo = formPersonalInfo.querySelectorAll("input, select");

  formPersonalInfo.addEventListener("submit", (e) => {
    e.preventDefault();

    if (!validateFormInputs(inputsPersonalInfo, [], false)) {
      return;
    }

    openCheckoutStep(1);
  });
}

if (formCheckoutAddress) {
  const inputsAddress = formCheckoutAddress.querySelectorAll("input, select");

  formCheckoutAddress.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (!validateFormInputs(inputsAddress, [], false)) {
      return;
    }

    const isGuestCheckout = Boolean(guestPlaceOrder);

    if (isGuestCheckout) {
      openCheckoutStep(2);
      return;
    }

    const hasSavedAddress = formCheckoutAddress.dataset.hasAddress === "true";

    if (!hasSavedAddress) {
      try {
        const response = await fetch(
          "/student014/boci/backend/endpoints/checkout_save_address.php",
          {
            method: "POST",
            body: new FormData(formCheckoutAddress),
            credentials: "include"
          }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || "No se pudo guardar la dirección.");
        }

        formCheckoutAddress.dataset.hasAddress = "true";

      } catch (error) {
        console.error(error);
        showMessage(error.message || "No se pudo guardar la dirección.", "error");
        return;
      }
    }

    openCheckoutStep(2);
  });
}

if (methodSelect && cardFields && googlePayFields) {
  const cardInputs = cardFields.querySelectorAll("input, select");
  const googlePayInputs = googlePayFields.querySelectorAll("input, select");

  function setPaymentFields() {
    const selectedMethod = methodSelect.value;

    if (selectedMethod === "card") {
      cardFields.style.display = "flex";
      googlePayFields.style.display = "none";

      cardInputs.forEach((input) => input.required = true);
      googlePayInputs.forEach((input) => input.required = false);

    } else if (selectedMethod === "google_pay") {
      cardFields.style.display = "none";
      googlePayFields.style.display = "flex";

      cardInputs.forEach((input) => input.required = false);
      googlePayInputs.forEach((input) => input.required = true);

    } else {
      cardFields.style.display = "none";
      googlePayFields.style.display = "none";

      cardInputs.forEach((input) => input.required = false);
      googlePayInputs.forEach((input) => input.required = false);
    }
  }

  methodSelect.addEventListener("change", setPaymentFields);
  setPaymentFields();
}

if (formCheckoutPayment) {
  const inputsPayment = formCheckoutPayment.querySelectorAll("input, select");

  formCheckoutPayment.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (!validateFormInputs(inputsPayment)) {
      return;
    }

    const isGuestCheckout = Boolean(guestPlaceOrder);

    if (isGuestCheckout) {
      openCheckoutStep(3);
      return;
    }

    const hasSavedPayment = formCheckoutPayment.dataset.hasPayment === "true";

    if (!hasSavedPayment) {
      try {
        const response = await fetch(
          "/student014/boci/backend/endpoints/checkout_save_payment.php",
          {
            method: "POST",
            body: new FormData(formCheckoutPayment),
            credentials: "include"
          }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || "No se pudo guardar el método de pago.");
        }

        formCheckoutPayment.dataset.hasPayment = "true";

        const cardNum = formCheckoutPayment.querySelector("#card_num");
        if (cardNum && cardNum.value.length >= 4) {
          cardNum.value = "************" + cardNum.value.slice(-4);
          cardNum.readOnly = true;
          cardNum.removeAttribute("required");
        }

      } catch (error) {
        console.error(error);
        showMessage(error.message || "No se pudo guardar el método de pago.", "error");
        return;
      }
    }

    openCheckoutStep(3);
  });
}

if (formPlaceOrder && !guestPlaceOrder) {
  formPlaceOrder.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const identificationInput = document.getElementById("identification");

      if (!identificationInput || !identificationInput.checkValidity()) {
        showMessage("Introduce un documento de identidad válido.", "error");
        identificationInput?.focus();
        return;
      }

      const response = await fetch(
        "/student014/boci/backend/endpoints/order_create.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          credentials: "include",
          body: JSON.stringify({
            identification: identificationInput ? identificationInput.value.trim() : ""
          })
        }
      );

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || "No se pudo crear el pedido.");
      }

      window.location.href =
        `/student014/boci/backend/forms/form_order_success.php?order_number=${encodeURIComponent(data.order_number)}`;

    } catch (error) {
      console.error(error);
      showMessage(error.message || "No se pudo procesar el pedido.", "error");
    }
  });
}

if (guestPlaceOrder) {
  guestPlaceOrder.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const payload = {
        personal_info: Object.fromEntries(new FormData(formPersonalInfo)),
        address: Object.fromEntries(new FormData(formCheckoutAddress)),
        payment: Object.fromEntries(new FormData(formCheckoutPayment)),
        cart: JSON.parse(localStorage.getItem("cart") || "[]")
      };

      const response = await fetch(
        "/student014/boci/backend/endpoints/order_create_guest.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          credentials: "include",
          body: JSON.stringify(payload)
        }
      );

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || "No se pudo crear el pedido.");
      }

      localStorage.removeItem("cart");

      window.location.href =
        `/student014/boci/backend/forms/form_order_success_guest.php?order_number=${encodeURIComponent(data.order_number)}`;

    } catch (error) {
      console.error(error);
      showMessage(error.message || "No se pudo procesar el pedido.", "error");
    }
  });
}

if (formNewAddress) {
  const inputsAddress = formNewAddress.querySelectorAll("input, select");

  formNewAddress.addEventListener("submit", (e) => {
    if (!validateFormInputs(inputsAddress, [], false)) {
      e.preventDefault();
    }
  });
}

if (formNewPayment) {
  const inputsPayment = formNewPayment.querySelectorAll("input, select");

  formNewPayment.addEventListener("submit", (e) => {
    if (!validateFormInputs(inputsPayment, ["is_default"])) {
      e.preventDefault();
    }
  });
}

if (formNewEmail) {
  const inputsEmail = formNewEmail.querySelectorAll("input");

  formNewEmail.addEventListener("submit", (e) => {
    if (!validateFormInputs(inputsEmail)) {
      e.preventDefault();
    }
  });
}

if (formNewPhone) {
  const inputsPhone = formNewPhone.querySelectorAll("input");

  formNewPhone.addEventListener("submit", (e) => {
    if (!validateFormInputs(inputsPhone)) {
      e.preventDefault();
    }
  });
}

if (formNewPassword) {
  const inputsPassword = formNewPassword.querySelectorAll("input");

  formNewPassword.addEventListener("submit", (e) => {
    if (!validateFormInputs(inputsPassword)) {
      e.preventDefault();
    }
  });
}

// gives colour to the page we are currently located on.
links.forEach(link => {
  const current = window.location.pathname;

  if (current === link.pathname) {
    link.classList.add('active');
  }
});

const addressRadios = document.querySelectorAll('input[name="selected_address_id"]');

if (addressRadios.length > 0) {
  addressRadios.forEach((radio) => {
    radio.addEventListener("change", async () => {
      const previousChecked = document.querySelector(
        'input[name="selected_address_id"][data-was-checked="true"]'
      );

      const formData = new FormData();
      formData.append("address_id", radio.value);

      try {
        const response = await fetch(
          "/student014/boci/backend/endpoints/select_address.php",
          {
            method: "POST",
            body: formData,
            credentials: "include"
          }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || "No se pudo actualizar la dirección.");
        }

        addressRadios.forEach((input) => {
          input.dataset.wasChecked = input.checked ? "true" : "false";
        });

        showMessage(data.message || "Dirección actualizada correctamente.", "success");

      } catch (error) {
        console.error(error);

        if (previousChecked) {
          previousChecked.checked = true;
        }

        showMessage(error.message || "No se pudo actualizar la dirección seleccionada.", "error");
      }
    });

    radio.dataset.wasChecked = radio.checked ? "true" : "false";
  });
}

const paymentMethodRadios = document.querySelectorAll(
  'input[name="selected_payment_method_id"]'
);

if (paymentMethodRadios.length > 0) {
  paymentMethodRadios.forEach((radio) => {
    radio.addEventListener("change", async () => {
      const previousChecked = document.querySelector(
        'input[name="selected_payment_method_id"][data-was-checked="true"]'
      );

      const formData = new FormData();
      formData.append("payment_method_id", radio.value);

      try {
        const response = await fetch(
          "/student014/boci/backend/endpoints/select_payment_method.php",
          {
            method: "POST",
            body: formData,
            credentials: "include"
          }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || "NO se pudo actualizar el método de pago");
        }

        paymentMethodRadios.forEach((input) => {
          input.dataset.wasChecked = input.checked ? "true" : "false";

          const option = input.closest(".saved-payment-option");
          if (option) {
            option.classList.toggle("default", input.checked);
          }
        });

        showMessage(
          data.message || "Método de pago actualizado correctamente.",
          "success"
        );

      } catch (error) {
        console.error(error);

        if (previousChecked) {
          previousChecked.checked = true;
        }

        showMessage(
          error.message || "No se pudo actualizar el método de pago seleccionado.",
          "error"
        );
      }
    });

    radio.dataset.wasChecked = radio.checked ? "true" : "false";
  });
}

if (btnViewOrders) {
  btnViewOrders.addEventListener('click', () => {
    window.location = '/student014/boci/backend/forms/orders.php';
  });
}

if (btnKeepShopping) {
  btnKeepShopping.addEventListener('click', () => {
    window.location = '/student014/boci/views/products.html';
  });
}

if (btnLogOut) {
  btnLogOut.addEventListener('click', () => {
    window.location = '/student014/boci/backend/db/db_logout.php';
  });
}

if (btnDeleteAddress.length > 0) {
  btnDeleteAddress.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      e.preventDefault();
      const addressOption = btn.closest(".saved-address-option");
      const addressInput = addressOption.querySelector(
        'input[name="selected_address_id"]'
      );

      if (addressInput.checked) {
        showMessage("No puedes eliminar la dirección seleccionada.", "warning");
        return;
      }

      const formData = new FormData();
      formData.append("address_id", addressInput.value);

      try {
        const response = await fetch(
          "/student014/boci/backend/endpoints/delete_address.php",
          {
            method: "POST",
            body: formData,
            credentials: "include"
          }
        );

        const data = await response.json();
        if (!response.ok || !data.success) {
          throw new Error(data.message || "No se pudo eliminar la dirección.");
        }

        addressOption.remove();
        showMessage(data.message || "Dirección eliminada correctamente.", "success");

        // needs to reload or update listing.

      } catch (error) {
        console.error(error);
        showMessage(error.message || "No se pudo eliminar la dirección.", "error");
      }
    });
  });
}

if (btnDeletePayment.length > 0) {
  btnDeletePayment.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      e.preventDefault();

      const paymentOption = btn.closest(".saved-payment-option");
      const paymentInput = paymentOption.querySelector(
        'input[name="selected_payment_method_id"]'
      );

      if (paymentInput.checked) {
        showMessage("No puedes eliminar el método de pago seleccionado.", "warning");
        return;
      }

      const formData = new FormData();
      formData.append("payment_method_id", paymentInput.value);

      try {
        const response = await fetch(
          "/student014/boci/backend/endpoints/delete_payment_method.php",
          {
            method: "POST",
            body: formData,
            credentials: "include"
          }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || "No se pudo eliminar el método de pago.");
        }

        paymentOption.remove();

        showMessage(
          data.message || "Método de pago eliminado correctamente.",
          "success"
        );

      } catch (error) {
        console.error(error);
        showMessage(
          error.message || "No se pudo eliminar el método de pago.",
          "error"
        );
      }
    });
  });
}

async function getCheckoutProducts() {
  const user = await getSessionUser();

  if (user?.loggedIn) {
    const response = await fetch(
      "/student014/boci/backend/endpoints/cart_frontend.php",
      { credentials: "include" }
    );

    if (!response.ok) {
      throw new Error("No se pudo cargar el carrito.");
    }

    return await response.json();
  }

  const rawCart = JSON.parse(localStorage.getItem("cart") || "[]");

  if (!Array.isArray(rawCart) || rawCart.length === 0) {
    return [];
  }

  const response = await fetch(
    "/student014/boci/backend/endpoints/product_frontend.php"
  );

  if (!response.ok) {
    throw new Error("No se pudieron cargar los productos.");
  }

  const products = await response.json();

  return rawCart
    .map((cartItem) => {
      const product = products.find(
        (p) => Number(p.product_id) === Number(cartItem.product_id)
      );

      if (!product) return null;

      return {
        ...product,
        quantity: cartItem.quantity
      };
    })
    .filter(Boolean);
}

async function renderCheckoutProducts() {
  const container = document.getElementById("checkout-products");

  if (!container) return;

  try {
    const products = await getCheckoutProducts();

    if (!products.length) {
      container.innerHTML = "<p>Tu carrito está vacío.</p>";
      return;
    }

    container.innerHTML = products.map((product) => `
      <article class="cart-item order-item">
        <img
          draggable="false"
          class="plush"
          src="${product.product_image}"
          alt="${product.product_name}"
        >

        <div class="quantity">
          <p>${product.product_name}</p>

          <div class="quantity-button">
            <span class="quantity-product">
              ${Number(product.quantity)}
            </span>
          </div>
        </div>

        <span class="price">
          ${Number(product.product_unit_price).toFixed(2)}€
        </span>
      </article>
    `).join("");

  } catch (error) {
    console.error(error);
    container.innerHTML = "<p>No se pudieron cargar los productos.</p>";
  }
}

renderCheckoutProducts();