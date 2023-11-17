<?php

$servername = "app-db";
$username = "root";
$password = "";
$dbname = "gpt";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
return $conn;

?>