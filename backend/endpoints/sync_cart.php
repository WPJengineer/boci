<?php

session_start();
// obtains customer_id and checks it against session.
// if the same gets the contents of the localStorage and then contents of shopping cart in database.
// if exists update quantity to be the sum of them both if doesnt exist adds new product.
header('Content-Type: application/json');
include('../config/db_config.php');

//checks for customer id.
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

//checks for cart.
if (!isset($input['cart']) || !is_array($input['cart'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid cart data'
    ]);
    exit;
}

$cart = $input['cart'];

?>