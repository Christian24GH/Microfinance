<?php
// db.php - Database connection
$conn = new mysqli("localhost", "root", "", "microfinance");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
