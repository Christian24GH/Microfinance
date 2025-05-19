<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['reportFile'])) {
    $targetDir = "uploads/reports/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // create folder if not exists
    }

    $fileName = basename($_FILES["reportFile"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allow only specific file types
    $allowedTypes = array("pdf", "doc", "docx");

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["reportFile"]["tmp_name"], $targetFilePath)) {
            // Insert file metadata into the database
            $stmt = $conn->prepare("INSERT INTO performance_reports (filename) VALUES (?)");
            $stmt->bind_param("s", $fileName);
            if ($stmt->execute()) {
                echo "<script>alert('Report uploaded successfully!'); window.location.href='index.php?page=samplePerformance';</script>";
            } else {
                echo "Database error: " . $stmt->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file type. Only PDF, DOC, DOCX allowed.";
    }
} else {
    echo "No file uploaded.";
}
?>
