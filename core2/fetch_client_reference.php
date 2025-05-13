<?php
require_once 'config/db.php'; // Include your database connection file

// Check if client_id is passed
if (isset($_GET['client_id'])) {
    $client_id = $_GET['client_id'];

    // Validate client_id (Ensure it's a number)
    if (!is_numeric($client_id)) {
        echo json_encode(['error' => 'Invalid client_id']);
        exit;
    }

    // Query to fetch client references for the given client_id
    $sql = "SELECT client_ref_id, firstname, lastname, relationship, contact, email FROM client_references WHERE client_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['error' => 'Failed to prepare statement']);
        exit;
    }

    // Bind the client_id parameter
    $stmt->bind_param("i", $client_id);  // Bind the client_id parameter as integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        $client_references = [];

        // Fetch results and store them in an array
        while ($row = $result->fetch_assoc()) {
            $client_references[] = $row;
        }

        // Return the data as JSON
        echo json_encode($client_references);
    } else {
        // If no records found, return an empty array
        echo json_encode([]);
    }

    $stmt->close();
} else {
    // If client_id is not provided, return an error message
    echo json_encode(['error' => 'No client_id provided']);
}

$conn->close();
?>
