// const btnHome = document.querySelector("#btnHome");
// const btnProducts = document.querySelector("#btnProducts");
// const btnAboutUs = document.querySelector("#btnAboutUs");
// const btnBlog = document.querySelector("#btnBlog");
const btnIncrease = document.getElementById("btnIncrease");
const btnDecrease = document.getElementById("btnDecrease");
const quantitySpan = document.getElementById("quantity-product");

const params = new URLSearchParams(window.location.search);
const productId = params.get("id");
let productData = null;

// if (!productId) {
//     console.error("No product id provided");
// }

console.log(productId);

// FUNCTIONS

loadProduct(productId);

async function loadProduct(productId) {
    try {
        const response = await fetch(
            // two locations that webpae is deployed to that accepts php
            // `https://juguetosboci.infinityfree.me/backend/endpoints/product_frontend.php`
            // `https://remotehost.es/student014/boci/backend/endpoints/product_frontend.php?id=${productId}`
            `http://localhost/boci/backend/endpoints/product_frontend.php`
        );
        const products = await response.json();
        const product = products.find(p => String(p.product_id) === String(productId));
        if (!product) throw new Error("Product not found");
        productData = product;
        renderProduct(product);
        console.log(product);

    } catch (err) {
        console.error(err);
    }
}

function renderProduct(p) {
    document.querySelector(".product-name").textContent = `Peluche '${p.product_name.toUpperCase()}'`;
    document.querySelector(".price").textContent = `${Number(p.product_unit_price).toFixed(2)} €`;
    document.querySelector(".product-image img").src = p.product_image;
    document.querySelector(".product-description").textContent = p.product_description ?? "";
}

// EVENTS

// btnHome.addEventListener('click', () => {
//     window.location.href = "../index.html";
// });

// btnProducts.addEventListener('click', () => {
//     window.location.href = "./products.html";
// });

// btnAboutUs.addEventListener('click', () => {
//     window.location.href = "./about.html";
// })

// btnBlog.addEventListener('click', () => {
//     window.location.href = "./blog.html";
// });

btnIncrease.addEventListener('click', () => {
    let quantity = parseInt(quantitySpan.textContent);
    quantity++;
    quantitySpan.textContent = quantity;
});

btnDecrease.addEventListener('click', () => {
    let quantity = parseInt(quantitySpan.textContent);

    if (quantity > 1) {
        quantity--;
        quantitySpan.textContent = quantity;
    }
});