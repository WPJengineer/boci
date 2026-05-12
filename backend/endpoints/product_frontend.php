<?php

header("Content-Type: application/json");

require(__DIR__ . '/../config/db_config.php');

$products = [];

if (isset($_GET['id']) && $_GET['id'] !== '') {
  $productId = intval($_GET['id']);

  $stmt = $conn->prepare("
    SELECT *
    FROM boci_products
    WHERE product_id = ?
  ");

  if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Could not prepare statement"]);
    exit;
  }

  $stmt->bind_param("i", $productId);
  $stmt->execute();

  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }

  $stmt->close();

} else {
  $stmt = $conn->prepare("
    SELECT *
    FROM boci_products
  ");

  if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Could not prepare statement"]);
    exit;
  }

  $stmt->execute();

  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }

  $stmt->close();
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);

$conn->close();
exit;

?>