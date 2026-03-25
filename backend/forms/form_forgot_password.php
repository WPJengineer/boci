<?php
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';
require($backend.'/header.php');
?>

<main>
  <a href="/boci/backend/forms/form_login.php"><img src="/boci/assets/icons/profile-icon-black.svg" alt="buy-icon" class="buy"></a>
  <form class="remember" action="/boci/backend/db/db_forgot_password.php" method="POST" novalidate>
    
  </form>
</main>

<?php
require($backend.'/footer.php');
?>