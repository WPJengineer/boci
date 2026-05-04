<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
  http_response_code(401);
  echo json_encode([
    "success" => false,
    "message" => "Not logged in"
  ]);
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];
$paymentMethodId = $_POST['payment_method_id'] ?? null;

if (!$paymentMethodId || !ctype_digit((string)$paymentMethodId)) {
  http_response_code(400);
  echo json_encode([
    "success" => false,
    "message" => "Invalid payment method"
  ]);
  exit();
}

$conn->begin_transaction();

try {
  $stmtCheck = $conn->prepare("
    SELECT payment_method_id
    FROM boci_payment_methods
    WHERE payment_method_id = ?
      AND customer_id = ?
      AND is_active = 1
    LIMIT 1
  ");

  $stmtCheck->bind_param("ii", $paymentMethodId, $customerId);
  $stmtCheck->execute();
  $result = $stmtCheck->get_result();

  if ($result->num_rows === 0) {
    throw new Exception("Payment method not found");
  }

  $stmtCheck->close();

  $stmtReset = $conn->prepare("
    UPDATE boci_payment_methods
    SET is_default = 0
    WHERE customer_id = ?
  ");

  $stmtReset->bind_param("i", $customerId);
  $stmtReset->execute();
  $stmtReset->close();

  $stmtUpdate = $conn->prepare("
    UPDATE boci_payment_methods
    SET is_default = 1
    WHERE payment_method_id = ?
      AND customer_id = ?
  ");

  $stmtUpdate->bind_param("ii", $paymentMethodId, $customerId);
  $stmtUpdate->execute();
  $stmtUpdate->close();

  $conn->commit();

  echo json_encode([
    "success" => true
  ]);
  exit();

} catch (Exception $e) {
  $conn->rollback();

  http_response_code(400);
  echo json_encode([
    "success" => false,
    "message" => $e->getMessage()
  ]);
  exit();
}