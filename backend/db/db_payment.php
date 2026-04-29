<?php

session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

include('../config/db_config.php');

$customerId = $_SESSION['customer_id'];
$method_type = $_POST['method_type'] ?? '';
$card_brand = trim($_POST['card_brand'] ?? '');
$card_last4 = trim($_POST['card_last4'] ?? '');
$exp_month = $_POST['exp_month'] ?? null;
$exp_year = $_POST['exp_year'] ?? null;
$is_default = isset($_POST['is_default']) ? 1 : 0;

if (!in_array($method_type, ['card', 'google_pay'])) {
  header("Location: /student014/boci/backend/forms/form_payment.php?error=invalid_method");
  exit();
}

if (!preg_match('/^[0-9]{4}$/', $card_last4)) {
  header("Location: /student014/boci/backend/forms/form_payment.php?error=invalid_card");
  exit();
}

if ($exp_month < 1 || $exp_month > 12 || $exp_year < 2026) {
  header("Location: /student014/boci/backend/forms/form_payment.php?error=invalid_expiry");
  exit();
}

$provider = 'mock';

$provider_customer_id = 'mock_customer_' . $customer_id;

$provider_payment_method_id = 'mock_' . $method_type . '_' . $customer_id . '_' . bin2hex(random_bytes(8));

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
  $customer_id,
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

$conn->commit();

header("Location: /student014/boci/backend/forms/form_payment.php?success=payment_added");
exit();

$conn->close();

?>