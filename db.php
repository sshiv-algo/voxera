<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "voxera_db"; // must match your phpMyAdmin database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
