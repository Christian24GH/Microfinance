<?php
// Database connection
$host = 'localhost';
$user = 'root';  // Replace with your DB username if different
$password = '';  // Replace with your DB password
$database = 'lown_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from form
    $client_id = $_POST['client_id'] ?? '';
    $new_status = $_POST['status'] ?? '';

    // Validate inputs
    if (!empty($client_id) && !empty($new_status)) {
        // Prepare and execute update query
        $stmt = $conn->prepare("UPDATE client_info SET status = ? WHERE client_id = ?");
        $stmt->bind_param("si", $new_status, $client_id);

        if ($stmt->execute()) {
            // Success - Redirect back or to a success page
            header("Location: ../social.php?status=updated");
            exit();
        } else {
            echo "Error updating status: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Client ID or Status missing.";
    }
}

$conn->close();
?>
