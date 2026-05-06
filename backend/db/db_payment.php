<?php

session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];
$method_type = $_POST['method_type'] ?? '';
$card_brand = trim($_POST['card_brand'] ?? '');
$card_last4 = trim($_POST['card_last4'] ?? '');
$exp_month = $_POST['exp_month'] ?? null;
$exp_year = $_POST['exp_year'] ?? null;
$is_default = isset($_POST['is_default']) ? 1 : 0;

if (!in_array($method_type, ['card', 'google_pay'])) {
  $_SESSION['error'] = "Método de pago no válido.";
  header("Location: /student014/boci/backend/forms/form_payment.php");
  exit();
}

if ($method_type === 'card') {
  if (!$card_brand || !preg_match('/^[0-9]{4}$/', $card_last4)) {
    $_SESSION['error'] = "Revisa los datos de la tarjeta.";
    header("Location: /student014/boci/backend/forms/form_payment.php");
    exit();
  }

  if ((int)$exp_month < 1 || (int)$exp_month > 12 || (int)$exp_year < 2026) {
    $_SESSION['error'] = "La fecha de vencimiento no es válida.";
    header("Location: /student014/boci/backend/forms/form_payment.php");
    exit();
  }
}

if ($method_type === 'google_pay') {
  $card_brand = null;
  $card_last4 = null;
  $exp_month = null;
  $exp_year = null;
}

$checkCount = $conn->prepare("
  SELECT COUNT(*) AS total
  FROM boci_payment_methods
  WHERE customer_id = ? AND is_active = 1
");
$checkCount->bind_param("i", $customerId);
$checkCount->execute();
$countResult = $checkCount->get_result()->fetch_assoc();
$checkCount->close();

if ((int)$countResult['total'] === 0) {
  $is_default = 1;
}

$provider = 'mock';

$provider_customer_id = 'mock_customer_' . $customerId;

$provider_payment_method_id = 'mock_' . $method_type . '_' . $customerId . '_' . bin2hex(random_bytes(8));

if ($is_default === 1) {
  $stmt_default = $conn->prepare("
    UPDATE boci_payment_methods
    SET is_default = 0
    WHERE customer_id = ?
  ");
  $stmt_default->bind_param("i", $customerId);
  $stmt_default->execute();
  $stmt_default->close();
}

$stmt = $conn->prepare("
  INSERT INTO boci_payment_methods (
    customer_id,
    provider,
    provider_customer_id,
    provider_payment_method_id,
    method_type,
    card_brand,
    card_last4,
    exp_month,
    exp_year,
    is_default,
    is_active
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
");

$stmt->bind_param(
  "issssssiii",
  $customerId,
  $provider,
  $provider_customer_id,
  $provider_payment_method_id,
  $method_type,
  $card_brand,
  $card_last4,
  $exp_month,
  $exp_year,
  $is_default
);

$stmt->execute();
$stmt->close();

$conn->close();

$_SESSION['success'] = "Método de pago guardado correctamente.";
header("Location: /student014/boci/backend/forms/form_payment.php");
exit();

?>