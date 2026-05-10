<?php

session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

$customerId = $_SESSION['customer_id'];
$email = trim($_POST['email'] ?? '');
$phoneNumber = trim($_POST['phone_number'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$email && !$phoneNumber && !$password) {
  $_SESSION['error'] = "Faltan campos obligatorios.";
  header("Location: /student014/boci/backend/forms/form_account.php");
  exit();
}

require(__DIR__ . '/../config/db_config.php');

try {

  $stmtCurrent = $conn->prepare("
    SELECT customer_email, customer_phone, customer_password
    FROM boci_customers
    WHERE customer_id = ?
    LIMIT 1
  ");

  $stmtCurrent->bind_param("i", $customerId);
  $stmtCurrent->execute();

  $result = $stmtCurrent->get_result();
  $currentDetails = $result->fetch_assoc();

  $stmtCurrent->close();

  if (!$currentDetails) {
    throw new Exception("Cliente no encontrado.");
  }

  $currentEmail = $currentDetails['customer_email'];
  $currentPhone = $currentDetails['customer_phone'];
  $currentPassword = $currentDetails['customer_password'];

  $updates = [];
  $params = [];
  $types = "";

  // EMAIL CHANGED
  if ($email && $email !== $currentEmail) {
    $updates[] = "customer_email = ?";
    $params[] = $email;
    $types .= "s";
  }

  // PHONE CHANGED
  if ($phoneNumber && $phoneNumber !== $currentPhone) {
    $updates[] = "customer_phone = ?";
    $params[] = $phoneNumber;
    $types .= "s";
  }

  // PASSWORD CHANGED
  if ($password && $password !== $currentPassword) {
    $updates[] = "customer_password = ?";
    $params[] = $password;
    $types .= "s";
  }

  // NOTHING CHANGED
  if (empty($updates)) {
    $_SESSION['warning'] = "No se han realizado cambios.";
    header("Location: /student014/boci/backend/forms/form_account.php");
    exit();
  }

  $params[] = $customerId;
  $types .= "i";

  $sql = "
    UPDATE boci_customers
    SET " . implode(", ", $updates) . "
    WHERE customer_id = ?
  ";

  $stmtUpdate = $conn->prepare($sql);

  $stmtUpdate->bind_param($types, ...$params);

  $stmtUpdate->execute();
  $stmtUpdate->close();

  $_SESSION['success'] = "Cuenta actualizada correctamente.";

} catch (Exception $e) {

  $_SESSION['error'] = "No se ha podido guardar la/s actualización/es.";

}

$conn->close();

header("Location: /student014/boci/backend/forms/form_account.php");
exit();

?>