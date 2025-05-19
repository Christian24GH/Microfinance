<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $conn->real_escape_string($_POST['userFullName']);
    $email = $conn->real_escape_string($_POST['userEmail']);
    $username = $conn->real_escape_string($_POST['userUsername']);
    $password = password_hash($_POST['userPassword'], PASSWORD_DEFAULT); // securely hashed
    $role = $conn->real_escape_string($_POST['userRole']);

    $sql = "INSERT INTO employee_users (full_name, email, username, password, role)
            VALUES ('$fullName', '$email', '$username', '$password', '$role')";

    if ($conn->query($sql)) {
        echo "Account created successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
