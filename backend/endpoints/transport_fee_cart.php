<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
  echo json_encode([
    'success' => true,
    'transport_fee_value' => 0
  ]);
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];

$stmt = $conn->prepare("
  SELECT btf.transport_fee_value
  FROM boci_address AS ba
  INNER JOIN boci_transport_fee AS btf
    ON ba.transport_fee_id = btf.transport_fee_id
  WHERE ba.customer_id = ?
    AND ba.selected = 1
  LIMIT 1
");

$stmt->bind_param("i", $customerId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode([
  'success' => true,
  'transport_fee_value' => $row ? (float)$row['transport_fee_value'] : 0
]);

?>