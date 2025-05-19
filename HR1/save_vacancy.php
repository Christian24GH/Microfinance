<?php
include 'db_connect.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$job_name = trim($_POST['job_name'] ?? '');
$availability = $_POST['availability'] ?? 0;
$description = $_POST['description'] ?? '';
$status = $_POST['status'] ?? 1; // Default to active

// 1. Insert job_name into positions table if it doesn't exist
if (!empty($job_name)) {
    $check_stmt = $conn->prepare("SELECT position_id FROM positions WHERE position_name = ?");
    $check_stmt->bind_param("s", $job_name);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
        // Not found, insert new position
        $insert_stmt = $conn->prepare("INSERT INTO positions (position_name) VALUES (?)");
        $insert_stmt->bind_param("s", $job_name);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $check_stmt->close();
}

// 2. Insert or update vacancy record
if ($id) {
    // Update existing
    $stmt = $conn->prepare("UPDATE vacancy SET position = ?, availability = ?, status = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sissi", $job_name, $availability, $status, $description, $id);
} else {
    // Insert new
    $stmt = $conn->prepare("INSERT INTO vacancy (position, availability, status, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $job_name, $availability, $status, $description);
}

if ($stmt->execute()) {
    header("Location: index.php?page=recruitment");
    exit;
} else {
    echo "Error saving job vacancy: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
