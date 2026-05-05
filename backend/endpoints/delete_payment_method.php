<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];
$paymentMethodId = $_POST['payment_method_id'] ?? null;

if (!$paymentMethodId || !ctype_digit($paymentMethodId)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
  exit();
}

$paymentMethodId = (int)$paymentMethodId;

try {
  $checkStmt = $conn->prepare("
    SELECT is_default
    FROM boci_payment_methods
    WHERE customer_id = ? AND payment_method_id = ? AND is_active = 1
  ");

  $checkStmt->bind_param("ii", $customerId, $paymentMethodId);
  $checkStmt->execute();
  $result = $checkStmt->get_result();

  if ($result->num_rows === 0) {
    throw new Exception("Payment method not found");
  }

  $row = $result->fetch_assoc();

  if ((int)$row['is_default'] === 1) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => 'No puedes eliminar el método de pago seleccionado.'
    ]);
    exit();
  }

  $checkStmt->close();

  $stmt = $conn->prepare("
    UPDATE boci_payment_methods
    SET is_active = 0
    WHERE customer_id = ? AND payment_method_id = ?
  ");

  $stmt->bind_param("ii", $customerId, $paymentMethodId);
  $stmt->execute();

  if ($stmt->affected_rows !== 1) {
    throw new Exception("Payment method not found");
  }

  $stmt->close();

  echo json_encode([
    'success' => true,
    'message' => 'Método de pago eliminado correctamente.'
  ]);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'No se ha podido eliminar el método de pago.'
  ]);
}

$conn->close();

?>