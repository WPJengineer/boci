<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <a href="/boci/backend/forms/form_login.php"><img src="/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <form class="new-register" action="/boci/backend/db/db_register.php" method="POST" novalidate>
    
  </form>
</main>

<?php
require($backend.'/footer.php');
?>