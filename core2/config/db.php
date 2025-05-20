<?php
// db.php - Database connection
$conn = new mysqli("localhost", "root", "", "lown_db");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
