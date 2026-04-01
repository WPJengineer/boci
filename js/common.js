const links = document.querySelectorAll('.header-menu ul li a');
const scrollContainer = document.querySelector(".scrollbar div");
const btnLeft = document.querySelector(".scroll-left");
const btnRight = document.querySelector(".scroll-right");
const SCROLL_AMOUNT = 220;
const btnShoppingCart = document.querySelector(".btnShoppingCart");
const counterCart = document.getElementById("counter");

function getCart() {
  try {
    const raw = localStorage.getItem("cart");
    const cart = raw ? JSON.parse(raw) : [];
    return Array.isArray(cart) ? cart : [];
  } catch {
    return [];
  }
}

function getCartCount() {
  const cart = getCart();
  return cart.reduce((total, item) => total + Number(item.quantity || 0), 0);
}

function updateCartBadge() {
  if (!counterCart) return;
  counterCart.textContent = getCartCount();
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
    window.location.href = "/boci/views/cart.html";
  })
};