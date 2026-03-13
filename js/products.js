const btnHome = document.querySelector("#btnHome");
const btnProducts = document.querySelector("#btnProducts");
const btnAboutUs = document.querySelector("#btnAboutUs");
const btnBlog = document.querySelector("#btnBlog");
const articles = document.querySelectorAll("article");

btnHome.addEventListener('click', () => {
    window.location.href = "../index.html";
});

btnProducts.addEventListener('click', () => {
    window.location.href = "./products.html";
});

btnAboutUs.addEventListener('click', () => {
    window.location.href = "./about.html";
})

btnBlog.addEventListener('click', () => {
    window.location.href = "./blog.html";
});

articles.forEach(article => {
    article.addEventListener("click", () => {
        window.location.href = `./product.html?id=${article.dataset.id}`;
    });
});