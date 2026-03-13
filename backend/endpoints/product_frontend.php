<?php

header("Content-Type: application/json");

// this shows all products like in the products home page
$sql = "SELECT *
FROM products
;";

// this shows a specific product if we select it from the products home page
if (isset($_GET['id']) && $_GET['id'] !== '') {
  $product_id = $_GET['id'];
  $sql .= " WHERE product_id = $product_id";
}

include('../config/db_config.php');

$result = mysqli_query($conn, $sql);

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