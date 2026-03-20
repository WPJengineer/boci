<?php

header("Content-Type: application/json");

// if (!isset($_GET['id']) || $_GET['id'] === '') {
//     echo json_encode(["error" => "Missing product id"]);
//     exit;
// }

// $product_id = intval($_GET['id']);

// this shows all products like in the products home page
$sql = "SELECT *
FROM `boci_products`";

// this shows a specific product if we select it from the products home page
if (isset($_GET['id']) && $_GET['id'] !== '') {
  $product_id = intval($_GET['id']);
  $sql .= " WHERE product_id = $product_id";
}

include('../config/db_config.php');

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        "error" => mysqli_error($conn),
        "query" => $sql
    ]);
    exit;
}

$products = [];

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }
}
echo json_encode($products);
mysqli_close($conn);
exit;
?>