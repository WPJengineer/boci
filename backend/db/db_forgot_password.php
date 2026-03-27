<?php

session_start();

$email = htmlspecialchars($_POST['email']);

include('../config/db_config.php');

$sql = "SELECT `customer_email`
FROM `boci_customers`
WHERE `customer_email` = '$email';";

$result = mysqli_query($conn, $sql);

if ($result) {
  if (mysqli_num_rows($result) > 0) {
    //here we start process to send change the password of the account to a temporary value we generate and then send this via email to the customer.
    
    header("Location: /boci/backend/forms/form_login.php");
    exit();
  } else {
    echo "No customer found with that email";
  }
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>