<?php
// db.php - Database connection
$conn = new mysqli("localhost", "root", "", "Microfinance");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
