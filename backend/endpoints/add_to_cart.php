<?php

session_start();
header('Content-Type: application/json');
include('../config/db_config.php');

// check if user is logged in.
if (!isset($_SESSION['customer_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'User not authenticated'
    ]);
    exit;
}

$customerId = $_SESSION['customer_id'];

//read json input from frontend
$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['product_id'];
$quantity = $input['quantity'];

// maybe need validation of inputs just in case for errors.

// insert or update cart.
$sql = "
    INSERT INTO boci_shopping_cart (customer_id, product_id, quantity)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to prepare statement',
        'error' => mysqli_error($conn)
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "iii", $customerId, $productId, $quantity);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error',
        'error' => mysqli_stmt_error($stmt)
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>