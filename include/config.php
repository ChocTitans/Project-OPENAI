<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gpt";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
return $conn;

?>