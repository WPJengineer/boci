<?php

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];

$stmt = $conn->prepare("
  SELECT *
  FROM boci_customers
  WHERE customer_id = ?
  LIMIT 1
");

$stmt->bind_param("i", $customerId);
$stmt->execute();

$result = $stmt->get_result();
$details = $result->fetch_assoc();

$stmt->close();

?>