<?php

// connect to database (remotehost.es)
$server_name = 'remotehost.es';
$user_name = 'dwess1234';
// $password = 'Usertest1234.';
$db_name = 'dwesdatabase';

$conn = mysqli_connect($server_name, $user_name, 'Usertest1234.', $db_name);

// connect to database (local)
// $conn = mysqli_connect('localhost', 'root', '', 'boci', 3306);

// check connection
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}

?>