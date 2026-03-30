<?php

header("Content-Type: application/json");

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

// trials

// header("Content-Type: application/json");

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// try {
//     require_once('../config/db_config.php');

//     if (!isset($conn) || !$conn) {
//         throw new Exception("Database connection not available");
//     }

//     $sql = "SELECT * FROM `boci_products`";

//     if (isset($_GET['id']) && $_GET['id'] !== '') {
//         $product_id = intval($_GET['id']);
//         $sql .= " WHERE product_id = $product_id";
//     }

//     $result = mysqli_query($conn, $sql);

//     $products = [];

//     while ($row = mysqli_fetch_assoc($result)) {
//         $products[] = $row;
//     }

//     echo json_encode($products);
// } catch (Throwable $e) {
//     http_response_code(500);
//     echo json_encode([
//         "error" => $e->getMessage()
//     ]);
// }

// exit;

?>