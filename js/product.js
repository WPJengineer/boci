const btnHome = document.querySelector("#btnHome");
const btnProducts = document.querySelector("#btnProducts");
const btnAboutUs = document.querySelector("#btnAboutUs");
const btnBlog = document.querySelector("#btnBlog");

const params = new URLSearchParams(window.location.search);
const productId = params.get("id");
let productData = null;

if (!productId) {
    console.error("No product id provided");
    return;
}

// FUNCTIONS

loadProduct(productId);

async function loadProduct(productId) {
    try {
        const response = await fetch(
            // need a webpage to retrieve data from
            `https://`
            // `https://remotehost.es/student014/shop/backend/endpoints/products_frontend.php`
        );
        const products = await response.json();
        const product = products.find(p => String(p.product_id) === String(productId));
        if (!product) throw new Error("Product not found");
        productData = product;
        renderProduct(product);

    } catch (err) {
        console.error(err);
    }
}

function renderProduct(p) {
    document.querySelector(".product-name").textContent = p.product_name;
    document.querySelector(".product-price").textContent =
        `${Number(p.product_unit_price).toFixed(2)} €`;

    document.querySelector(".product-image").src = p.product_image;
    document.querySelector(".product-description").textContent =
        p.product_description ?? "";
}

// EVENTS

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