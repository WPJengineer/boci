const links = document.querySelectorAll('.header-menu ul li a');
const scrollContainer = document.querySelector(".scrollbar div");
const btnLeft = document.querySelector(".scroll-left");
const btnRight = document.querySelector(".scroll-right");
const SCROLL_AMOUNT = 220;
const btnShoppingCart = document.querySelector(".btnShoppingCart");

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

btnShoppingCart.addEventListener('click', () => {
  window.location.href = "/boci/views/cart.html";
});