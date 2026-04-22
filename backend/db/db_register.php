<?php

session_start();

$gender = htmlspecialchars($_POST['gender']);
$name = htmlspecialchars($_POST['name']);
$lastname = htmlspecialchars($_POST['lastname']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$privacy = ($_POST['privacy'] === 'on') ? 1 : 0;
$newsletter = (($_POST['newsletter'] ?? '') === 'on') ? 1 : 0;

include('../config/db_config.php');

$sql = 
  "INSERT INTO `boci_customers` 
    (`customer_gender`,
    `customer_forename`,
    `customer_lastname`,
    `customer_email`,
    `customer_password`,
    `customer_privacy`,
    `customer_newsletter`,
    `customer_role`)
  VALUES
    ('$gender',
    '$name',
    '$lastname',
    '$email',
    '$password',
    '$privacy',
    '$newsletter',
    'customer');";

// echo $gender, $name, $lastname, $email, $password, $privacy, $newsletter;

if (mysqli_query($conn, $sql)) {
  // redirection after correctly registering - need to figure out if redirect to page we where located on last or go to a specific page.
  header("Location: /student014/boci/index.html");
  exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>