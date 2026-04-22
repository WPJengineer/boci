// const btnHome = document.querySelector("#btnHome");
// const btnProducts = document.querySelector("#btnProducts");
// const btnAboutUs = document.querySelector("#btnAboutUs");
// const btnBlog = document.querySelector("#btnBlog");
const btnIncrease = document.getElementById("btnIncrease");
const btnDecrease = document.getElementById("btnDecrease");
const quantitySpan = document.getElementById("quantity-product");
const btnAddToCart = document.querySelector(".add-to-cart button");

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
            `https://remotehost.es/student014/boci/backend/endpoints/product_frontend.php?id=${productId}`
            // `http://localhost/boci/backend/endpoints/product_frontend.php`
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
    document.querySelector(".show-image img").src = p.product_image;
    document.querySelector(".perspectives img:nth-child(1)").src = p.product_image;
    document.querySelector(".perspectives img:nth-child(2)").src = p.product_perspective_1 ?? "";
    document.querySelector(".perspectives img:nth-child(3)").src = p.product_perspective_2 ?? "";
    document.querySelector(".product-description").textContent = p.product_description ?? "";

    setupImageGallery();
}

function setupImageGallery() {
    const mainImage = document.querySelector(".show-image img");
    const perspectiveImages = document.querySelectorAll(".perspectives img");

    perspectiveImages.forEach(img => {
        img.addEventListener("click", () => {
            if (!img.src) return;
            mainImage.src = img.src;
        });
    });
}

// this is only for the guest cart, need to add for when logged in.
// need to add function to combine localStorage with db after log in if we start to shop before logging in.
async function addToCart(productId, quantity = 1) {
  const user = await getSessionUser();

  if (user?.loggedIn) {
    //call the backend if user is logged in.
    try {
      const response = await fetch(
        "http://localhost/boci/backend/endpoints/add_to_cart.php",
        `https://remotehost.es/student014/boci/backend/endpoints/add_to_cart.php`,
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
        throw new Error(data.message || "Could not add product to cart");
      }

      return data;
    } catch (error) {
      console.error("Error adding to DB cart:", error);
      throw error;
    }
    // delete localStorage cart.
  }

  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  productId = Number(productId);
  quantity = Number(quantity);

  const existingItem = cart.find(item => item.product_id === productId);

  if (existingItem) {
    existingItem.quantity += quantity;
  } else {
    cart.push({ product_id: productId, quantity: quantity });
  }

  localStorage.setItem('cart', JSON.stringify(cart));
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

btnAddToCart.addEventListener('click', async () => {
    try {
        const quantity = parseInt(quantitySpan.textContent);
        await addToCart(productId, quantity);
        window.location.href = "./products.html";
    } catch (error) {
        console.log(error);
    }
});