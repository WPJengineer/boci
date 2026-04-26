const password = document.querySelector(".password-field input");
const showPassword = document.querySelector(".btn-show-password");
const btnShoppingCart = document.querySelector(".btnShoppingCart");
const counterCart = document.getElementById("counter");
const cartParams = new URLSearchParams(window.location.search);
const btnLogOut = document.querySelector(".btnLogOut");

if (cartParams.get("clearCart") === "1") {
  localStorage.removeItem("cart");
}

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

// missing to check for if valid entry into form

const formLogin = document.querySelector(".login");
const formNewRegister = document.querySelector(".new-register");
const formNewAddress = document.querySelector(".address");
const formNewPayment = document.querySelector(".payment");
const formNewEmail = document.querySelector(".account-email");
const formNewPhone = document.querySelector(".account-phone");
const links = document.querySelectorAll('.pages p a');

if (formLogin) {
  const inputsLogin = formLogin.querySelectorAll("input");
  const cartInput = document.getElementById("cart_data") || "[]";
  formLogin.addEventListener("submit", (e) => {
    cartInput.value = localStorage.getItem("cart") || "[]";
    inputsLogin.forEach((input) => {
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
      }
    });

    // prevent submit if invalid
    if (!formLogin.checkValidity()) {
      e.preventDefault();
    }
  });
}

if (formNewRegister) {
  const inputsRegister = formNewRegister.querySelectorAll("input");
  formNewRegister.addEventListener("submit", (e) => {
    let formIsValid = true;
    inputsRegister.forEach((input) => {
      if (input.name === "newsletter") return;
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
        formIsValid = false;
      }
    });

    // prevent submit if invalid
    if (!formIsValid) {
      e.preventDefault();
    }
  });
}

if (formNewAddress) {
  const inputsAddress = formNewAddress.querySelectorAll("input");
  formNewAddress.addEventListener("submit", (e) => {
    inputsAddress.forEach((input) => {
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
      }
    });

    // prevent submit if invalid
    if (!formNewAddress.checkValidity()) {
      e.preventDefault();
    }
  });
}

if (formNewPayment) {
  const inputsPayment = formNewPayment.querySelectorAll("input");
  formNewPayment.addEventListener("submit", (e) => {
    inputsPayment.forEach((input) => {
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
      }
    });

    // prevent submit if invalid
    if (!formNewPayment.checkValidity()) {
      e.preventDefault();
    }
  });
}

if (formNewEmail) {
  const inputsEmail = formNewEmail.querySelectorAll("input");
  formNewEmail.addEventListener("submit", (e) => {
    inputsEmail.forEach((input) => {
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
      }
    });

    // prevent submit if invalid
    if (!formNewEmail.checkValidity()) {
      e.preventDefault();
    }
  });
}

if (formNewPhone) {
  const inputsPhone = formNewPhone.querySelectorAll("input");
  formNewPhone.addEventListener("submit", (e) => {
    inputsPhone.forEach((input) => {
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
      }
    });

    // prevent submit if invalid
    if (!formNewPhone.checkValidity()) {
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

btnLogOut.addEventListener('click', () => {
  window.location = '/student014/boci/backend/db/db_logout.php';
});