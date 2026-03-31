const btnContinueShoppping = document.querySelector(".continue-shopping button");

// FUNCTIONS

function getGuestCart() {
  try {
    const raw = localStorage.getItem("cart");
    const items = raw ? JSON.parse(raw) : [];
    return Array.isArray(items) ? items : [];
  } catch {
    return [];
  }
}

async function loadCart() {

  const cartContainer = document.querySelector(".shopping-cart");

  try {
    const user = await getSessionUser();
    if (user.loggedIn) {
      const response = await fetch(`http://localhost/boci/backend/endpoints/cart_frontend.php`, {credentials: "include"});
      const products = await response.json();
      cartContainer.innerHTML = "";
      renderCart(products, cartContainer);
      attachListeners();
      return;
    }

    const guestCart = getGuestCart();
    if (!guestCart.length) {
      cartContainer.innerHTML = '<p>Your cart is empty.</p>';
      return;
    }
    const response = await fetch(`http://localhost/boci/backend/endpoints/product_frontend.php`);
    const products = await response.json();
    const items = guestCart.map(cartItem => {
      const product = products.find(p => Number(p.product_id) === Number(cartItem.product_id));
      return product ? {...product, quantity: cartItem.quantity} : null;
    }).filter(Boolean);
    cartContainer.innerHTML = "";
    renderCart(items, cartContainer);
    attachListeners();

  } catch (error) {
    console.error(error);
    cartContainer.innerHTML = '<p>Error loading cart.</p>';
  }

}

function renderCart(products, cartContainer) {

  cartContainer.innerHTML = products.map(product => `
    <article class="cart-item" data-id="${product.product_id}">
      <img class="plush" src="${product.product_image}" alt="peluche-image">
      <div class="quantity">
        <p>${product.product_name}</p>
        <div class="quantity-button">
          <span class="quantity-product">${product.quantity}</span>
          <div class="buttons">
            <button class="btnIncrease">
              <img src="../assets/icons/chevron-up.svg" alt="up-arrow">
            </button>
            <button class="btnDecrease">
              <img src="../assets/icons/chevron-down.svg" alt="down-arrow">
            </button>
          </div>
        </div>
      </div>
      <span class="price">${product.product_unit_price}€</span>
      <button class="btnDelete">
        <img class="icon remove" src="../assets/icons/close-icon-yellow.svg" alt="remove-icon">
      </button>
      </article>
    `).join('');
}

function attachListeners() {
  document.querySelectorAll(".cart-item").forEach(product => {
    const productId = product.dataset.id;

    const quantity = product.querySelector(".quantity-product");
    const btnIncrease = product.querySelector(".btnIncrease");
    const btnDecrease = product.querySelector(".btnDecrease");
    const btnDelete = product.querySelector(".btnDelete");

    btnIncrease.addEventListener("click", () => {
      const newTotal = Number(quantity.textContent) + 1;
      quantity.textContent = newTotal;
      updateQuantity(productId, newTotal);
      // updateSubtotal(product);
      // updateTotal();
    });

    btnDecrease.addEventListener("click", () => {
      const qty = Number(quantity.textContent);
      if (qty <= 1) return;
      const newTotal = qty - 1;
      quantity.textContent = newTotal;
      updateQuantity(productId, newTotal);
      // updateSubtotal(product);
      // updateTotal();
    });

    btnDelete.addEventListener("click", () => {
      product.remove();
      removeFromGuestCart(productId);
      // updateTotal();
      const container = document.querySelector(".shopping-cart");
      if (container.querySelectorAll(".cart-item").length === 0) {
        container.innerHTML = "<p>Your cart is empty.</p>";
      }
    });
  });
  // updateTotal();
}

function updateQuantity(productId, quantity) {
  const cart = getGuestCart();
  const index = cart.findIndex(i => String(i.product_id) === String(productId));
  if (index >= 0) {
    cart[index].quantity = quantity;
    localStorage.setItem('cart', JSON.stringify(cart));
  }
}

function removeFromGuestCart(productId) {
  const cart = getGuestCart();
  const updatedCart = cart.filter(
    item => String(item.product_id) !== String(productId)
  );

  if (updatedCart.length === 0) {
    localStorage.removeItem('cart');
  } else {
    localStorage.setItem('cart', JSON.stringify(updatedCart));
  }
}

// function updateSubtotal(product) {
//   const quantity = +product.querySelector('.quantity-product').textContent;
//   const unitText = product.querySelector('.price')?.textContent || "0€";
//   const unitPrice = +unitText.replace("€", "").trim() || 0;
//   const subtotal = product.querySelector('.product-subtotal');
//   if (subtotal) {
//     subtotal.textContent = (quantity * unitPrice).toFixed(2) + "€";
//   }
// }

// function updateTotal() {
//   let total = 0;
//   document.querySelectorAll(".product-subtotal").forEach(product => {
//     total += +product.textContent.replace("€", "").trim();
//   });
//   const shoppingCartTotal = document.querySelector(".subtotal span");
//   if (shoppingCartTotal) shoppingCartTotal.textContent = total.toFixed(2) + "€";
// }

loadCart();

// EVENTS

btnContinueShoppping.addEventListener('click', () => {
  window.location.href = "/boci/views/products.html";
});

