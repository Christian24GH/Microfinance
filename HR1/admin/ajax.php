<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_recruitment_status"){
	$save = $crud->save_recruitment_status();
	if($save)
		echo $save;
}
if($action == "delete_recruitment_status"){
	$save = $crud->delete_recruitment_status();
	if($save)
		echo $save;
}
if($action == "save_vacancy"){
	$save = $crud->save_vacancy();
	if($save)
		echo $save;
}
if($action == "delete_vacancy"){
	$save = $crud->delete_vacancy();
	if($save)
		echo $save;
}
if($action == "save_application"){
	$save = $crud->save_application();
	if($save)
		echo $save;
}
if($action == "delete_application"){
	$save = $crud->delete_application();
	if($save)
		echo $save;
}

if ($_GET['action'] == 'save_application') {
    include 'db_connect.php';

    // Sanitize inputs
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $middlename = $conn->real_escape_string($_POST['middlename']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $cover_letter = isset($_POST['cover_letter']) ? $conn->real_escape_string($_POST['cover_letter']) : '';
    $position_id = (int) $_POST['position_id'];

    // File upload handling
    $resume_path = '';
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $upload_dir = '../uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = time() . '_' . basename($_FILES['resume']['name']);
        $file_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['resume']['tmp_name'], $file_path)) {
            $resume_path = 'uploads/resumes/' . $filename; // relative path for DB
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Resume upload failed.']);
            exit;
        }
    }

    // Insert into application table
    $stmt = $conn->prepare("INSERT INTO application (firstname, middlename, lastname, gender, email, contact, address, cover_letter, position_id, resume_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $firstname, $middlename, $lastname, $gender, $email, $contact, $address, $cover_letter, $position_id, $resume_path);

    if ($stmt->execute()) {
        echo 1;
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}


