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
$addressId = $_POST['address_id'] ?? null;

if (!$addressId || !ctype_digit($addressId)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid address']);
  exit();
}

$addressId = (int)$addressId;

try {
  $conn->begin_transaction();
  $stmt = $conn->prepare("
    SELECT address_id
    FROM boci_address
    WHERE customer_id = ? AND address_id = ?
    LIMIT 1
  ");
  $stmt->bind_param("ii", $customerId, $addressId);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows !== 1) {
    throw new Exception("Address not found");
  }

  $stmt->close();

  $stmt = $conn->prepare("
    UPDATE boci_address
    SET selected = 0
    WHERE customer_id = ?
  ");
  $stmt->bind_param("i", $customerId);
  $stmt->execute();
  $stmt->close();

  $stmt = $conn->prepare("
    UPDATE boci_address
    SET selected = 1
    WHERE customer_id = ? AND address_id = ?
  ");
  $stmt->bind_param("ii", $customerId, $addressId);
  $stmt->execute();
  $stmt->close();

  $conn->commit();

  echo json_encode(['success' => true]);

} catch (Exception $e) {
  $conn->rollback();

  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'Could not update selected address'
  ]);
}

$conn->close();

?>