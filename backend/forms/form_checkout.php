<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: /boci/backend/forms/form_login.php");
    exit();
}

?>