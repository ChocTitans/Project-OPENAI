<?php

$servername = "185.98.131.160";
$username = "heave2049889";
$password = "Xberetax123@";
$dbname ="heave2049889";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
return $conn;

?>