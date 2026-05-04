<?php

session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: /student014/boci/backend/forms/form_login.php");
  exit();
}

$customerId = $_SESSION['customer_id'];
$country = trim($_POST['country'] ?? '');
$state = trim($_POST['state'] ?? '');
$city = trim($_POST['city'] ?? '');
$postCode = trim($_POST['postal_code'] ?? '');
$streetName = trim($_POST['street_name'] ?? '');
$streetNum = trim($_POST['street_num'] ?? '');

// echo $customerId, $country, $state, $city, $postCode, $streetName, $streetNum;

if (!$country || !$state || !$city || !$postCode || !$streetName) {
  $_SESSION['error'] = "Faltan campos obligatorios.";
  header("Location: /student014/boci/backend/forms/form_address.php");
  exit();
}

require(__DIR__ . '/../config/db_config.php');

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

$resultFee = $stmtFee->get_result();
$fee = $resultFee->fetch_assoc();

if (!$fee) {
  $_SESSION['error'] = "No se ha encontrado una tarifa de envío válida.";
  header("Location: /student014/boci/backend/forms/form_address.php");
  exit();
}

$transportFeeId = $fee['transport_fee_id'];
$stmtFee->close();

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
    $streetName,
    $streetNum,
    $city,
    $state,
    $postCode,
    $country
  );

  $stmt->execute();
  $stmt->close();

  $conn->commit();

  $_SESSION['success'] = "Dirección guardada correctamente.";
  header("Location: /student014/boci/backend/forms/form_address.php");
  exit();

} catch (Exception $e) {
  $conn->rollback();
  $_SESSION['error'] = "No se ha podido guardar la dirección.";
  header("Location: /student014/boci/backend/forms/form_address.php");
  exit();
}

$conn->close();

?>