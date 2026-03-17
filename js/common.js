const links = document.querySelectorAll('.header-menu ul li a');

links.forEach(link => {
  const current = window.location.pathname;
  const linkPath = link.pathname;

  if (current === linkPath || (current.includes('product.html') && linkPath.includes('products'))) {
    link.classList.add('active');
  }
});