<?php

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];

$stmt = $conn->prepare("
  SELECT payment_method_id, provider, method_type, card_brand, card_last4, exp_month, exp_year, is_default
  FROM boci_payment_methods
  WHERE customer_id = ? AND is_active = 1
  ORDER BY is_default DESC, created_at DESC
");

$stmt->bind_param("i", $customerId);
$stmt->execute();

$result = $stmt->get_result();
$payment_methods = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

?>