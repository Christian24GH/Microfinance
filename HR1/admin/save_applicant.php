<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and assign POST values
    $firstname     = trim($_POST['firstname']);
    $middlename    = trim($_POST['middlename']);
    $lastname      = trim($_POST['lastname']);
    $gender        = trim($_POST['gender']);
    $email         = trim($_POST['email']);
    $contact       = trim($_POST['contact']);
    $address       = trim($_POST['address']);
    $cover_letter  = trim($_POST['cover_letter']);
    $position_id   = intval($_POST['position_id']);

    // Handle file upload
    $resume_path = '';
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // create directory if it doesn't exist
        }

        $filename = basename($_FILES['resume']['name']);
        $target_file = $upload_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $filename);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow only certain file formats
        $allowed_types = ['pdf', 'doc', 'docx'];
        if (!in_array($file_type, $allowed_types)) {
            die('Invalid file type. Only PDF, DOC, and DOCX are allowed.');
        }

        if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
            $resume_path = $target_file;
        } else {
            die('Failed to upload resume.');
        }
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO application 
        (firstname, middlename, lastname, gender, email, contact, address, cover_letter, position_id, resume_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $firstname, $middlename, $lastname, $gender, $email, $contact, $address, $cover_letter, $position_id, $resume_path);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Failed to save applicant: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request.';
}

header("Location: index.php?page=applicant_management");
exit();

?>
