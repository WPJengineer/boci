<?php
session_start();

// if (!isset($_SESSION['customer_id']) || !isset($_SESSION['customer_role']) || $_SESSION['customer_role'] !== 'admin') {
//     header("Location: /student014/boci/backend/forms/form_login.php?error=admin_required");
//     exit();
// }

require(__DIR__.'/../header.php');

?>

<main>
    <a href="/student014/boci/backend/forms/form_login.php"><img draggable="false" src="/student014/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
    <button class="add-product">
      <img draggable="false" src="/student014/boci/assets/icons/plus-icon.svg" alt="add-product-icon">
      Añadir producto
    </button>
    <div class="products">
        <!-- have to retrieve all of products from the database and build the products screen, they need an A tag between article and image/p -->
        <article class="peluches" data-id="1">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/vaquita-lita.svg" alt="peluche-image">
            <p>LITA</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="2">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/gina.svg" alt="peluche-image">
            <p>GINA</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="3">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/floreti.svg" alt="peluche-image">
            <p>FLORETI</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="4">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/pincetes.svg" alt="peluche-image">
            <p>PINCETES</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="5">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/quesito-tendre.svg" alt="peluche-image">
            <p>TENDRE</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="6">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/deta.svg" alt="peluche-image">
            <p>DETA</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="7">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/quesito-semi.svg" alt="peluche-image">
            <p>SEMI</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="8">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/neta.svg" alt="peluche-image">
            <p>NETA</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="9">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/floquet.svg" alt="peluche-image">
            <p>FLOQUET</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="10">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/gueta.svg" alt="peluche-image">
            <p>GÜETA</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
        <article class="peluches" data-id="11">
            <img draggable="false" src="/student014/boci/assets/images/image-plushes/old/quesito-curat.svg" alt="peluche-image">
            <p>CURAT</p>
            <div class="admin-buttons">
              <button>
                <img draggable="false" src="/student014/boci/assets/icons/edit-icon-white.svg" alt="edit-icon">
                Editar
              </button>
              <button class="delete">
                <img draggable="false" src="/student014/boci/assets/icons/delete-icon.svg" alt="delete-icon">  
                Eliminar
              </button>
            </div>
        </article>
    </div>
    <button class="btnShoppingCart">
        <img draggable="false" src="/student014/boci/assets/icons/shopping-bag-icon.svg" alt="shopping-bag-icon">
        <span id="counter">0</span>
    </button>
    <button class="btnLogOut">
        <img draggable="false" class="icon" src="/student014/boci/assets/icons/logout-icon-black.svg" alt="log-out-icon">
    </button>
</main>

<?php
require(__DIR__.'/../footer.php');
?>