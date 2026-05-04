<?php

session_start();
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$cartData = $_POST['cart_data'] ?? '[]';

require(__DIR__ . '/../config/db_config.php');

$sql = "SELECT *
FROM `boci_customers`
WHERE `customer_email` = '$email' AND
`customer_password` = '$password';";

$result = mysqli_query($conn, $sql);

if ($result) {
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['customer_id'] = $row['customer_id'];
    $_SESSION['username'] = $row['customer_forename'];
    $_SESSION['userLastname'] = $row['customer_lastname'];
    $_SESSION['customer_role'] = $row['customer_role'];

    //update shopping cart in DB if there is one in localStorage and then delete from localStorage.
    $cart = json_decode($cartData, true);
    if (is_array($cart) && count($cart) > 0) {
        $sqlCart = "
            INSERT INTO boci_shopping_cart (customer_id, product_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
        ";

        $stmtCart = mysqli_prepare($conn, $sqlCart);

        if ($stmtCart) {
            foreach ($cart as $item) {
                $productId = isset($item['product_id']) ? (int)$item['product_id'] : 0;
                $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 0;
                $customerId = (int)$row['customer_id'];

                if ($productId > 0 && $quantity > 0) {
                    mysqli_stmt_bind_param($stmtCart, "iii", $customerId, $productId, $quantity);
                    mysqli_stmt_execute($stmtCart);
                }
            }

            mysqli_stmt_close($stmtCart);
        }
    }

    $redirect = $_POST['redirect'] ?? '/student014/boci/backend/forms/orders.php';

    //option for redirection for admin vs customer later.
    header("Location: $redirect?clearCart=1");
    exit();

  } else  {
      echo "No customer found with that username and/or password";
  }
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>