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
  $checkStmt = $conn->prepare("
    SELECT selected
    FROM boci_address
    WHERE customer_id = ? AND address_id = ?
  ");

  $checkStmt->bind_param("ii", $customerId, $addressId);
  $checkStmt->execute();
  $result = $checkStmt->get_result();

  if ($result->num_rows === 0) {
    throw new Exception("Address not found");
  }

  $row = $result->fetch_assoc();

  if ((int)$row['selected'] === 1) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => 'No puedes eliminar la dirección seleccionada.'
    ]);
    exit();
  }

  $checkStmt->close();

  $stmt = $conn->prepare("
    DELETE FROM boci_address
    WHERE customer_id = ? AND address_id = ?
  ");

  $stmt->bind_param("ii", $customerId, $addressId);
  $stmt->execute();

  if ($stmt->affected_rows !== 1) {
    throw new Exception("Address not found");
  }

  $stmt->close();

  echo json_encode([
    'success' => true,
    'message' => 'Dirección eliminada correctamente.'
  ]);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'No se ha podido eliminar la dirección.'
  ]);
}

$conn->close();

?>