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

$registerForm = "/student014/boci/backend/forms/form_register.php";

if (!empty($redirect)) {
  $registerForm .= "?redirect=" . urlencode($redirect);
}

if ($gender === '' || $name === '' || $lastname === '' || $email === '' || $password === '') {
  $_SESSION['error'] = "Por favor, rellena todos los campos obligatorios.";
  header("Location: " . $registerForm);
  exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error'] = "El email no es válido.";
  header("Location: " . $registerForm);
  exit();
}

if ($privacy !== 1) {
  $_SESSION['error'] = "Debes aceptar la política de privacidad.";
  header("Location: " . $registerForm);
  exit();
}

// Check if email already exists
$checkEmail = $conn->prepare("
  SELECT customer_id 
  FROM boci_customers 
  WHERE customer_email = ?
  LIMIT 1
");

$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$emailResult = $checkEmail->get_result();

if ($emailResult->num_rows > 0) {
  $checkEmail->close();

  $_SESSION['error'] = "Ya existe una cuenta con este email.";
  header("Location: " . $registerForm);
  exit();
}

$checkEmail->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$role = 'customer';

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
    "sssssiis",
    $gender,
    $name,
    $lastname,
    $email,
    $hashedPassword,
    $privacy,
    $newsletter,
    $role
);

if ($stmt->execute()) {

  $customer_id = $conn->insert_id;

  $_SESSION['customer_id'] = $customer_id;
  $_SESSION['username'] = $name;
  $_SESSION['userLastname'] = $lastname;
  $_SESSION['customer_role'] = $role;

  $_SESSION['success'] = "Cuenta creada correctamente.";

  $stmt->close();
  $conn->close();

  if (!empty($redirect)) {
    header("Location: " . $redirect . "?clearCart=1");
  } else {
    header("Location: /student014/boci/index.html");
  }

  exit();

} else {

  $_SESSION['error'] = "No se pudo crear la cuenta. Inténtalo de nuevo.";

  $stmt->close();
  $conn->close();

  header("Location: " . $registerForm);
  exit();

}

?>