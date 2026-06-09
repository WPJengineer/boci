<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
  http_response_code(401);
  echo json_encode([
    'success' => false,
    'message' => 'No autorizado.'
  ]);
  exit();
}

require(__DIR__ . '/../config/db_config.php');

$customerId = $_SESSION['customer_id'];

$methodType = trim($_POST['method_type'] ?? '');
$cardNum = trim($_POST['card_num'] ?? '');
$expMonth = trim($_POST['exp_month'] ?? '');
$expYear = trim($_POST['exp_year'] ?? '');
$googlePayEmail = trim($_POST['google_pay_email'] ?? '');

if (!$methodType) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => 'Selecciona un método de pago.'
  ]);
  exit();
}

if ($methodType === 'card') {
  if (!$cardNum || !$expMonth || !$expYear) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => 'Faltan datos de la tarjeta.'
    ]);
    exit();
  }

  if (!preg_match('/^[0-9]{16}$/', $cardNum)) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => 'Introduce un número de tarjeta válido.'
    ]);
    exit();
  }

  $providerPaymentMethodId = 'mock_card_' . $customerId . '_' . time();
}

if ($methodType === 'google_pay') {
  if (!$googlePayEmail || !filter_var($googlePayEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => 'Introduce un correo válido de Google Pay.'
    ]);
    exit();
  }

  $providerPaymentMethodId = 'mock_google_pay_' . $customerId . '_' . time();
  $cardNum = null;
  $expMonth = null;
  $expYear = null;
}

$conn->begin_transaction();

try {
  $stmtReset = $conn->prepare("
    UPDATE boci_payment_methods
    SET is_default = 0
    WHERE customer_id = ?
  ");

  $stmtReset->bind_param("i", $customerId);
  $stmtReset->execute();
  $stmtReset->close();

  $stmt = $conn->prepare("
  INSERT INTO boci_payment_methods
  (
      customer_id,
      provider,
      provider_payment_method_id,
      method_type,
      card_num,
      exp_month,
      exp_year,
      is_default,
      is_active
  )
  VALUES (?, 'mock', ?, ?, ?, ?, ?, 1, 1)
  ");

  $stmt->bind_param(
    "isssii",
    $customerId,
    $providerPaymentMethodId,
    $methodType,
    $cardNum,
    $expMonth,
    $expYear
  );

  $stmt->execute();
  $stmt->close();

  $conn->commit();

  echo json_encode([
    'success' => true,
    'message' => 'Método de pago guardado correctamente.'
  ]);

} catch (Exception $e) {
  $conn->rollback();

  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'No se pudo guardar el método de pago.'
  ]);
}

$conn->close();

?>