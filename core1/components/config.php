<?php
$server = "localhost";
$user = "root";
$password = "";
$dbname = "microfinance";

// Create connection
$conn = new mysqli($server, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>