<?php

session_start();
// $redirect = $_POST['redirect'] ?? '/boci/backend/forms/form_profile.php';
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';

include('../config/db_config.php');

$sql = "SELECT *
FROM `boci_customers`
WHERE `customer_email` = '$email' AND
`customer_password` = '$password';";

$result = mysqli_query($conn, $sql);

if ($result) {
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['customer_id'] = $row['customer_id'];
    $_SESSION['username'] = $row['customer_forename'];
    $_SESSION['userLastname'] = $row['customer_lastname'];
    $_SESSION['customer_role'] = $row['customer_role'];

    $redirect = $_POST['redirect'] ?? '/boci/backend/forms/form_profile.php';

    //option for redirection for admin vs customer later.
    header("Location: $redirect");
    exit();

  } else  {
      echo "No customer found with that username and/or password";
  }
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>