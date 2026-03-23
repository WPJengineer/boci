<?php

session_start();

$gender = htmlspecialchars($_POST['gender']);
$name = htmlspecialchars($_POST['name']);
$lastname = htmlspecialchars($_POST['lastname']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$privacy = $_POST['privacy'];
$newsletter = $_POST['newsletter'];
$backend = $_SERVER['DOCUMENT_ROOT'].'/boci/backend';

include('../config/db_config.php');

$sql = ";";

mysqli_close($conn);

?>