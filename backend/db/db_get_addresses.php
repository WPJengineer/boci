<?php

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

include('../config/db_config.php');

$customerId = $_SESSION['customer_id'];

$stmt = $conn->prepare("
  SELECT address_id, country, state, city, postal_code, street, number, selected
  FROM boci_address
  WHERE customer_id = ?
  ORDER BY selected DESC, address_id DESC
");

$stmt->bind_param("i", $customerId);
$stmt->execute();

$result = $stmt->get_result();
$addresses = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

?>