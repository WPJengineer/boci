<?php

session_start();

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$cartData = $_POST['cart_data'] ?? '[]';

require(__DIR__ . '/../config/db_config.php');

$sql = "
  SELECT *
  FROM boci_customers
  WHERE customer_email = ?
    AND customer_password = ?
  LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $email, $password);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);

  $_SESSION['customer_id'] = $row['customer_id'];
  $_SESSION['username'] = $row['customer_forename'];
  $_SESSION['userLastname'] = $row['customer_lastname'];
  $_SESSION['customer_role'] = $row['customer_role'];

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

  $_SESSION['success'] = "¡Bienvenido de nuevo, " . $row['customer_forename'] . "!";

  $redirect = $_POST['redirect'] ?? '/student014/boci/backend/forms/orders.php';

  header("Location: $redirect?clearCart=1");
  exit();

} else {
  $_SESSION['error'] = "Credenciales incorrectas.";
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>