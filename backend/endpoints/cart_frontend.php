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

$customer_id = $_SESSION["customer_id"];

$sql = "SELECT sc.product_id, p.product_name, p.product_unit_price, p.product_image, sc.quantity
FROM `boci_shopping_cart` AS sc
INNER JOIN `boci_products` AS p ON sc.product_id = p.product_id
WHERE `customer_id` = '$customer_id'";

require(__DIR__ . '/../config/db_config.php');

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        "error" => mysqli_error($conn),
        "query" => $sql
    ]);
    exit;
}

$cart = [];

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $cart[] = $row;
  }
}
echo json_encode($cart);
mysqli_close($conn);
exit;

?>