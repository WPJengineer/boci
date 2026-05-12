<?php

header("Content-Type: application/json");

session_start();

if (!isset($_SESSION['customer_id'])) {
  http_response_code(401);

  echo json_encode([
    'success' => false,
    'message' => 'User not authenticated'
  ]);

  exit;
}

$customerId = $_SESSION['customer_id'];

require(__DIR__ . '/../config/db_config.php');

$stmt = $conn->prepare("
  SELECT
    sc.product_id,
    p.product_name,
    p.product_unit_price,
    p.product_image,
    sc.quantity
  FROM boci_shopping_cart AS sc
  INNER JOIN boci_products AS p
    ON sc.product_id = p.product_id
  WHERE sc.customer_id = ?
");

if (!$stmt) {
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not prepare statement'
  ]);

  exit;
}

$stmt->bind_param("i", $customerId);

if (!$stmt->execute()) {
  http_response_code(500);

  echo json_encode([
    'success' => false,
    'message' => 'Could not execute query'
  ]);

  $stmt->close();
  $conn->close();

  exit;
}

$result = $stmt->get_result();

$cart = [];

while ($row = $result->fetch_assoc()) {
  $cart[] = $row;
}

echo json_encode($cart, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();

exit;

?>