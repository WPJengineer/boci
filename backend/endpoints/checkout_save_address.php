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

$street = trim($_POST['address-line1'] ?? '');
$number = trim($_POST['address-line2'] ?? '');
$postCode = trim($_POST['postal_code'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$country = trim($_POST['country'] ?? '');

if (!$street || !$postCode || !$city || !$state || !$country) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => 'Faltan campos obligatorios.'
  ]);
  exit();
}

function getTransportZone($country, $postCode) {
  $country = strtoupper(trim($country));
  $postCode = trim($postCode);

  if ($country !== 'ES') {
    return 'international';
  }

  if (str_starts_with($postCode, '077')) {
    return 'local';
  }

  return 'national';
}

$transportZone = getTransportZone($country, $postCode);

$stmtFee = $conn->prepare("
  SELECT transport_fee_id
  FROM boci_transport_fee
  WHERE transport_zone = ?
  LIMIT 1
");

$stmtFee->bind_param("s", $transportZone);
$stmtFee->execute();

$fee = $stmtFee->get_result()->fetch_assoc();
$stmtFee->close();

if (!$fee) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => 'No se encontró una tarifa de envío válida.'
  ]);
  exit();
}

$transportFeeId = $fee['transport_fee_id'];

$conn->begin_transaction();

try {
  $stmtReset = $conn->prepare("
    UPDATE boci_address
    SET selected = 0
    WHERE customer_id = ?
  ");

  $stmtReset->bind_param("i", $customerId);
  $stmtReset->execute();
  $stmtReset->close();

  $stmt = $conn->prepare("
    INSERT INTO boci_address
    (
      customer_id,
      transport_fee_id,
      street,
      number,
      city,
      state,
      postal_code,
      country,
      selected
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
  ");

  $stmt->bind_param(
    "iissssss",
    $customerId,
    $transportFeeId,
    $street,
    $number,
    $city,
    $state,
    $postCode,
    $country
  );

  $stmt->execute();
  $stmt->close();

  $conn->commit();

  echo json_encode([
    'success' => true,
    'message' => 'Dirección guardada correctamente.'
  ]);

} catch (Exception $e) {
  $conn->rollback();

  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'No se pudo guardar la dirección.'
  ]);
}

$conn->close();

?>