<?php

// connect to database (remotehost.es)
$server_name = 'remotehost.es';
$user_name = 'dwess1234';
// $password = 'Usertest1234.';
$db_name = 'dwesdatabase';

// $conn = mysqli_connect($server_name, $user_name, 'Usertest1234.', $db_name);

// connect to database (local)
$conn = mysqli_connect('localhost', 'root', '', 'boci', 3307);
// for tirals later with remote
// var_dump($conn);exit;
// check connection
if (!$conn) {
    header("Content-Type: application/json");
    echo json_encode([
        "error" => "Connection failed",
        "details" => mysqli_connect_error()
    ]);
    exit;
}

?>