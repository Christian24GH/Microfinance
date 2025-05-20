<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_id'])) {
    $resumeId = intval($_POST['resume_id']);

    // Get the file path
    $getPathQuery = "SELECT resume_path FROM application WHERE id = $resumeId";
    $result = $conn->query($getPathQuery);

    if ($result && $result->num_rows > 0) {
        $resume = $result->fetch_assoc();
        $filePath = $resume['resume_path'];

        // Delete file from server
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove resume_path from database
        $updateQuery = "UPDATE application SET resume_path = '' WHERE id = $resumeId";
        $conn->query($updateQuery);
    }
}

header("Location: index.php?page=sampleNewhired"); // redirect back
exit;
