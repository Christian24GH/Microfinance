<?php
include "config.php";

// Restrict access if not logged in
if (!isset($_SESSION["id"])) {
    header("Location: ../testapp/index.php");
    exit();
}

// Fetch role name from the database
$role_id = $_SESSION["role_id"];
$sql = "SELECT role_name FROM roles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $role_id);
$stmt->execute();
$result = $stmt->get_result();
$role_name = ($result->num_rows > 0) ? $result->fetch_assoc()['role_name'] : "Unknown";

// Fetch email
$id = $_SESSION["id"];
$sql = "SELECT email FROM accounts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$email = ($result->num_rows > 0) ? $result->fetch_assoc()['email'] : "Unknown";
?>