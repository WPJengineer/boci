<?php

session_start();
header('Content-Type: application/json');

require(__DIR__ . '/../config/db_config.php');

if (!isset($_SESSION['customer_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'User not authenticated'
    ]);
    exit;
}

$customerId = $_SESSION['customer_id'];
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON input'
    ]);
    exit;
}

$productId = $input['product_id'];
$quantity = $input['quantity'];

$sql = "UPDATE boci_shopping_cart
        SET quantity = ?
        WHERE customer_id = ? AND product_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to prepare statement'
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "iii", $quantity, $customerId, $productId);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'success' => true,
        'message' => 'Cart quantity updated'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>