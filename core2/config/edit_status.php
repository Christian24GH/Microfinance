<?php
include 'db.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['client_id'], $_POST['status'])) {
        $client_id = intval($_POST['client_id']);
        $status = $_POST['status'];

        $allowed_statuses = ['Active', 'Inactive', 'Flagged'];
        if (!in_array($status, $allowed_statuses)) {
            echo "Invalid status value.";
            exit;
        }

        $sql = "UPDATE client_info SET loan_stat = ? WHERE client_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $client_id);

        if ($stmt->execute()) {
            // Redirect back to modal page with success message
            header("Location: ../social.php?msg=Status updated successfully");
            exit;
        } else {
            echo "Error updating status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Missing client_id or status.";
    }
} else {
    echo "Invalid request method.";
}
?>
