const btnHome = document.querySelector("#btnHome");
const btnProducts = document.querySelector("#btnProducts");
const btnAboutUs = document.querySelector("#btnAboutUs");

btnHome.addEventListener('click', () => {
    window.location.href = "/index.html";
});

btnProducts.addEventListener('click', () => {
    window.location.href = "./views/products.html";
});

btnAboutUs.addEventListener('click', () => {
    window.location.href = "./views/about.html";
});