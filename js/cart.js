const btnContinueShoppping = document.querySelector(".continue-shopping button");
const checkoutForm = document.getElementById("checkout-form");
const numArticles = document.querySelector(".num-articles span");
const articlePrice = document.querySelector(".article-price span");
const transportPrice = document.querySelector(".transport-price span");

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

/*this function fetches the total number of articles from database,
calculates the total cost by retrieving values of each product and multiplying by its price,
retrieves cost of transport by retrieving location they post to (local, national or international)*/
function updateCart(products) {
  const totalArticles = products.reduce((sum, product) => {
    return sum + Number(product.quantity || 0);
  }, 0);
  numArticles.textContent = totalArticles;
  articlePrice.textContent = (totalArticles * 12.95).toFixed(2);
  /* this needs to go fetch price from database for each item in future, for showing purposes because all prices are currently the same it works */
  // transportPrice.textContent = ;
}

async function loadCart() {
  const cartContainer = document.querySelector(".shopping-cart");

  try {
    const user = await getSessionUser();
    if (user?.loggedIn) {
      const response = await fetch(
        // `http://localhost/boci/backend/endpoints/cart_frontend.php`,
        `https://remotehost.es/student014/boci/backend/endpoints/cart_frontend.php`,
        {credentials: "include"});
      const products = await response.json();
      cartContainer.innerHTML = "";
      renderCart(products, cartContainer);
      return;
    }

    const guestCart = getGuestCart();
    if (!guestCart.length) {
      cartContainer.innerHTML = '<p>Tu carrito esta vacio.</p>';
      return;
    }
    const response = await fetch(
      // `http://localhost/boci/backend/endpoints/product_frontend.php`
      `https://remotehost.es/student014/boci/backend/endpoints/product_frontend.php`
    );

    const products = await response.json();
    const items = guestCart.map(cartItem => {
      const product = products.find(p => Number(p.product_id) === Number(cartItem.product_id));
      return product ? {...product, quantity: cartItem.quantity} : null;
    }).filter(Boolean);
    cartContainer.innerHTML = "";
    renderCart(items, cartContainer);

  } catch (error) {
    console.error(error);
    cartContainer.innerHTML = '<p>Error en cargar carrito.</p>';
  }

}

function renderCart(products, cartContainer) {

  if (!products.length) {
    cartContainer.innerHTML = "<p>Tu carrito está vacío.</p>";
    updateCart([]);
    return;
  }

  cartContainer.innerHTML = products.map(product => `
    <article class="cart-item" data-id="${product.product_id}">
      <img class="plush" src="${product.product_image}" alt="peluche-image">
      <div class="quantity">
        <p>${product.product_name}</p>
        <div class="quantity-button">
          <span class="quantity-product">${product.quantity}</span>
          <div class="buttons">
            <button class="btnIncrease">
              <img src="/student014/boci/assets/icons/chevron-up.svg" alt="up-arrow">
            </button>
            <button class="btnDecrease">
              <img src="/student014/boci/assets/icons/chevron-down.svg" alt="down-arrow">
            </button>
          </div>
        </div>
      </div>
      <span class="price">${product.product_unit_price}€</span>
      <button class="btnDelete">
        <img class="icon remove" src="/student014/boci/assets/icons/close-icon-yellow.svg" alt="remove-icon">
      </button>
    </article>
  `).join('');

  updateCart(products);
  attachListeners(products, cartContainer);
}

function attachListeners(products, cartContainer) {
  document.querySelectorAll(".cart-item").forEach(cartItem => {
    const productId = cartItem.dataset.id;
    const btnIncrease = cartItem.querySelector(".btnIncrease");
    const btnDecrease = cartItem.querySelector(".btnDecrease");
    const btnDelete = cartItem.querySelector(".btnDelete");

    btnIncrease.addEventListener("click", async () => {
      const product = products.find(p => String(p.product_id) === String(productId));
      if (!product) return;
      product.quantity = Number(product.quantity) + 1;
      await updateQuantity(productId, product.quantity);
      renderCart(products, cartContainer);
      // updateSubtotal(product);
      // updateTotal();
    });

    btnDecrease.addEventListener("click", async () => {
      const product = products.find(p => String(p.product_id) === String(productId));
      if (!product || Number(product.quantity) <= 1) return;
      product.quantity = Number(product.quantity) - 1;
      await updateQuantity(productId, product.quantity);
      renderCart(products, cartContainer);
      // updateSubtotal(product);
      // updateTotal();
    });

    btnDelete.addEventListener("click", async () => {
      await removeFromCart(productId);
      const updatedProducts = products.filter(
        p => String(p.product_id) !== String(productId)
      );
      renderCart(updatedProducts, cartContainer);
      // removeFromGuestCart(productId);
      // updateTotal();
      const container = document.querySelector(".shopping-cart");
      if (container.querySelectorAll(".cart-item").length === 0) {
        container.innerHTML = "<p>Your cart is empty.</p>";
      }
    });
  });
  // updateTotal();
}

async function updateQuantity(productId, quantity) {
  try {
    const user = await getSessionUser();
    if (user?.loggedIn) {
      const response = await fetch(
        // `http://localhost/boci/backend/endpoints/cart_update.php`,
        `https://remotehost.es/student014/boci/backend/endpoints/cart_update.php`,
        {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        credentials: "include",
        body: JSON.stringify({
          product_id: productId,
          quantity: quantity
        })
      });
      const data = await response.json();
      if (!response.ok || !data.success) {
        throw new Error(data.message || "Could not update cart");
      }
      return data;
    }

    const cart = getGuestCart();
    const index = cart.findIndex(i => String(i.product_id) === String(productId));
    if (index >= 0) {
      cart[index].quantity = quantity;
      localStorage.setItem('cart', JSON.stringify(cart));
    }

  } catch (error) {
    console.error(error);
    throw error;
  }
}

async function removeFromCart(productId) {
  try {
    const user = await getSessionUser();

    if (user?.loggedIn) {
      const response = await fetch(
        // "http://localhost/boci/backend/endpoints/cart_delete.php",
        "https://remotehost.es/student014/boci/backend/endpoints/cart_delete.php",
        {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        credentials: "include",
        body: JSON.stringify({
          product_id: Number(productId)
        })
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || "Could not remove item from cart");
      }

      return data;
    }

    removeFromGuestCart(productId);

  } catch (error) {
    console.error(error);
    throw error;
  }
}

function removeFromGuestCart(productId) {
  const cart = getGuestCart();
  const updatedCart = cart.filter(
    item => String(item.product_id) !== String(productId)
  );

  if (!updatedCart.length) {
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
  window.location.href = "/student014/boci/views/products.html";
});

checkoutForm.addEventListener('submit', async(e) => {
  e.preventDefault();

  // missing to check if cart is empty it does nothing.
  const user = await getSessionUser();
  window.location.href = "/student014/boci/backend/forms/form_login.php?redirect=/student014/boci/backend/forms/form_checkout.php";
  return;
  
});