<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

// Required fields
$required = ['id', 'position_id', 'lastname', 'firstname', 'gender', 'email', 'contact', 'address', 'process_id'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo "Error: Missing required field '$field'.";
        exit;
    }
}

// Sanitize input
$id = intval($_POST['id']);
$position_id = intval($_POST['position_id']);
$process_id = intval($_POST['process_id']);
$lastname = trim($_POST['lastname']);
$firstname = trim($_POST['firstname']);
$middlename = isset($_POST['middlename']) ? trim($_POST['middlename']) : '';
$gender = $_POST['gender'];
$email = trim($_POST['email']);
$contact = trim($_POST['contact']);
$address = trim($_POST['address']);
$cover_letter = isset($_POST['cover_letter']) ? trim($_POST['cover_letter']) : '';

// Get current resume path
$stmt = $conn->prepare("SELECT resume_path FROM application WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Applicant not found.";
    exit;
}

$applicant = $result->fetch_assoc();
$current_resume = $applicant['resume_path'];
$resume_path = $current_resume;

// Handle resume upload
if (isset($_FILES['resume']) && $_FILES['resume']['error'] !== UPLOAD_ERR_NO_FILE) {
    $allowed_types = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    $file_type = mime_content_type($_FILES['resume']['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        echo "Invalid file type for resume. Allowed types: PDF, DOC, DOCX.";
        exit;
    }

    $upload_dir = 'uploads/resumes/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
    $new_filename = 'resume_' . $id . '_' . time() . '.' . $ext;
    $target_path = $upload_dir . $new_filename;

    if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_path)) {
        $resume_path = $target_path;

        if (!empty($current_resume) && file_exists($current_resume) && $current_resume !== $resume_path) {
            unlink($current_resume);
        }
    } else {
        echo "Failed to upload resume file.";
        exit;
    }
}

// Update applicant info including status_id
$stmt = $conn->prepare("
    UPDATE application 
    SET position_id = ?, process_id = ?, lastname = ?, firstname = ?, middlename = ?, 
        gender = ?, email = ?, contact = ?, address = ?, cover_letter = ?, resume_path = ?
    WHERE id = ?
");

$stmt->bind_param(
    "iisssssssssi",
    $position_id,
    $process_id,
    $lastname,
    $firstname,
    $middlename,
    $gender,
    $email,
    $contact,
    $address,
    $cover_letter,
    $resume_path,
    $id
);

if ($stmt->execute()) {
    header('Location: index.php?page=applicant_management');
    exit;
} else {
    echo "Failed to update applicant: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
