<?php

session_start();

$gender = htmlspecialchars($_POST['gender']);
$name = htmlspecialchars($_POST['name']);
$lastname = htmlspecialchars($_POST['lastname']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$privacy = ($_POST['privacy'] === 'on') ? 1 : 0;
$newsletter = ($_POST['newsletter'] === 'on') ? 1 : 0;
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';

include('../config/db_config.php');

$sql = 
  "INSERT INTO `boci_customers` 
    (`customer_gender`,
    `customer_forename`,
    `customer_lastname`,
    `customer_email`,
    `customer_password`,
    `customer_privacy`,
    `customer_newsletter`)
  VALUES
    ('$gender',
    '$name',
    '$lastname',
    '$email',
    '$password',
    '$privacy',
    '$newsletter');";

echo $gender, $name, $lastname, $email, $password, $privacy, $newsletter;

if (mysqli_query($conn, $sql)) {
    echo
        'Customer details inserted successfully';
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>