<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = 1; // Hardcoded sender for now; replace with $_SESSION later
    $receiver_id = $_POST['employee_id'];
    $type = $conn->real_escape_string($_POST['recognition_type']);
    $message = $conn->real_escape_string($_POST['message']);

    // Badge Upload
    $badge_path = '';
    if (isset($_FILES['badge']) && $_FILES['badge']['error'] == 0) {
        $target_dir = "uploads/";
        $filename = basename($_FILES["badge"]["name"]);
        $target_file = $target_dir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES["badge"]["tmp_name"], $target_file)) {
            $badge_path = $target_file;
        }
    }

    $sql = "INSERT INTO social_recognitions (sender_id, receiver_id, recognition_type, message, badge_path)
            VALUES ('$sender_id', '$receiver_id', '$type', '$message', '$badge_path')";

    if ($conn->query($sql)) {
        header("Location: index.php?page=sampleSocial");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
