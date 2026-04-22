<?php

header("Content-Type: application/json");

include('../config/db_config.php');

// this shows all products like in the products home page
$sql = "SELECT * FROM `boci_products`";

// this shows a specific product if we select it from the products home page
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $product_id = intval($_GET['id']);
    $sql .= " WHERE `product_id` = $product_id";
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "error" => mysqli_error($conn),
        "query" => $sql
    ]);
    exit;
}

$products = [];

// if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }
// }

$json = json_encode($products, JSON_UNESCAPED_UNICODE);

echo $json;

mysqli_close($conn);
exit;

?>