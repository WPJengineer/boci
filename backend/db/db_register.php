<?php

session_start();

require(__DIR__ . '/../config/db_config.php');

$gender = trim($_POST['gender']);
$name = trim($_POST['name']);
$lastname = trim($_POST['lastname']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$privacy = ($_POST['privacy'] === 'on') ? 1 : 0;
$newsletter = (($_POST['newsletter'] ?? '') === 'on') ? 1 : 0;
$redirect = $_POST['redirect'] ?? '';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = 
  "INSERT INTO `boci_customers` 
    (`customer_gender`,
    `customer_forename`,
    `customer_lastname`,
    `customer_email`,
    `customer_password`,
    `customer_privacy`,
    `customer_newsletter`,
    `customer_role`)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

$role = 'customer';

mysqli_stmt_bind_param(
  $stmt,
    "sssssiss",
    $gender,
    $name,
    $lastname,
    $email,
    $hashedPassword,
    $privacy,
    $newsletter,
    $role
);

if (mysqli_stmt_execute($stmt)) {

    $customer_id = mysqli_insert_id($conn);

    // Auto login after registration
    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['username'] = $name;
    $_SESSION['userLastname'] = $lastname;
    $_SESSION['customer_role'] = $role;

    // Redirect logic
    if (!empty($redirect)) {
        header("Location: " . $redirect . "?clearCart=1");
    } else {
        header("Location: /student014/boci/index.html");
    }

    exit();

} else {

    echo "Database error: " . mysqli_error($conn);

}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>