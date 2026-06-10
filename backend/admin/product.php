<?php
session_start();

if (!isset($_SESSION['customer_id']) || !isset($_SESSION['customer_role']) || $_SESSION['customer_role'] !== 'admin') {
    header("Location: /student014/boci/backend/forms/form_login.php?error=admin_required");
    exit();
}

require(__DIR__.'/../header.php');

?>

<main>
  
</main>

<?php
require(__DIR__.'/../footer.php');
?>