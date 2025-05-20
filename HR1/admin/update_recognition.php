<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $recognition_type = $_POST['recognition_type'];
  $message = $_POST['message'];
  $badge_path = '';

  // Handle badge file upload if exists
  if (isset($_FILES['badge']) && $_FILES['badge']['error'] === 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["badge"]["name"]);
    move_uploaded_file($_FILES["badge"]["tmp_name"], $target_file);
    $badge_path = $target_file;
  }

  // Update query
  if ($badge_path) {
    $stmt = $conn->prepare("UPDATE social_recognitions SET recognition_type = ?, message = ?, badge_path = ? WHERE id = ?");
    $stmt->bind_param("sssi", $recognition_type, $message, $badge_path, $id);
  } else {
    $stmt = $conn->prepare("UPDATE social_recognitions SET recognition_type = ?, message = ? WHERE id = ?");
    $stmt->bind_param("ssi", $recognition_type, $message, $id);
  }

  $stmt->execute();
  $stmt->close();
}

header("Location: index.php?page=sampleSocial");
exit;
