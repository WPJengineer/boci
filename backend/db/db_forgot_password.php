<?php

session_start();

$email = trim($_POST['email'] ?? '');

if (!$email) {
  $_SESSION['error'] = "Introduce tu correo electrónico.";
  header("Location: /student014/boci/backend/forms/form_forgot_password.php");
  exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error'] = "Introduce un correo electrónico válido.";
  header("Location: /student014/boci/backend/forms/form_forgot_password.php");
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$stmt = $conn->prepare("
  SELECT customer_email
  FROM boci_customers
  WHERE customer_email = ?
  LIMIT 1
");

if (!$stmt) {
  $_SESSION['error'] = "No se ha podido procesar la solicitud.";
  header("Location: /student014/boci/backend/forms/form_forgot_password.php");
  exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$customer = $result->fetch_assoc();

$stmt->close();
$conn->close();

if ($customer) {
  // Aquí empezaría el proceso para generar una contraseña temporal
  // o un token de recuperación y enviarlo por correo.

  $_SESSION['success'] = "Si existe una cuenta con ese correo, recibirás instrucciones para recuperar tu contraseña.";
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
} else {
  $_SESSION['error'] = "No se ha encontrado ningún cliente con ese correo.";
  header("Location: /student014/boci/backend/forms/form_forgot_password.php");
  exit();
}

?>