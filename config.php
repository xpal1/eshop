<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "eshop-db";

$conn = new mysqli("$servername", "$username", "$password", "$database");

if($conn->connect_error){
    die("Connection Failed!".$conn->connect_error);
}

?>
