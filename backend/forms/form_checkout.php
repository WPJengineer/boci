<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: /student014/boci/backend/forms/form_login.php?redirect=/student014/boci/backend/forms/form_checkout.php");
    exit();
}

$customerId = $_SESSION['customer_id'];



?>