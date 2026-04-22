const links = document.querySelectorAll('.header-menu ul li a');
const scrollContainer = document.querySelector(".scrollbar div");
const btnLeft = document.querySelector(".scroll-left");
const btnRight = document.querySelector(".scroll-right");
const SCROLL_AMOUNT = 220;
const btnShoppingCart = document.querySelector(".btnShoppingCart");
const counterCart = document.getElementById("counter");
const cartParams = new URLSearchParams(window.location.search);

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

links.forEach(link => {
  const current = window.location.pathname;
  const linkPath = link.pathname;

  if (current === linkPath || (current.includes('product.html') && linkPath.includes('products'))) {
    link.classList.add('active');
  }
});

if (btnRight) {
  btnRight.addEventListener("click", () => {
    scrollContainer.scrollBy({
        left: SCROLL_AMOUNT,
        behavior: "smooth"
    });
  });
}

if (btnLeft) {
  btnLeft.addEventListener("click", () => {
      scrollContainer.scrollBy({
          left: -SCROLL_AMOUNT,
          behavior: "smooth"
      });
  });
}

if (btnShoppingCart) {
  btnShoppingCart.addEventListener('click', () => {
    window.location.href = "/student014/boci/views/cart.html";
  })
};