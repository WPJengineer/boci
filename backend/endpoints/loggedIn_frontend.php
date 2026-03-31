<?php
header("Content-Type: application/json");
session_start();

if (!isset($_SESSION["customer_id"])) {
  echo json_encode(["loggedIn" => false]);
  exit;
}

echo json_encode([
  "loggedIn" => true,
  "customer_id" => $_SESSION["customer_id"]
]);
exit;
?>