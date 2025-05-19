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

if (isset($_GET['action']) && $_GET['action'] == 'save_application') {
    include 'db_connect.php';

    $target_dir = "uploads/";
    $file_path = "";

    // Handle resume file upload
    if (!empty($_FILES['resume']['name'])) {
        $filename = time() . '_' . basename($_FILES["resume"]["name"]);
        $file_path = $target_dir . $filename;

        // Create uploads folder if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move uploaded file to uploads/ directory
        if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $file_path)) {
            echo 0; // File upload failed
            exit;
        }
    }

    // Insert applicant data into database
    $stmt = $conn->prepare("INSERT INTO application 
        (firstname, middlename, lastname, gender, email, contact, address, cover_letter, position_id, resume_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssss", 
        $_POST['firstname'], 
        $_POST['middlename'], 
        $_POST['lastname'], 
        $_POST['gender'], 
        $_POST['email'], 
        $_POST['contact'], 
        $_POST['address'], 
        $_POST['cover_letter'], 
        $_POST['position_id'], 
        $file_path
    );

    if ($stmt->execute()) {
        echo 1; // success
    } else {
        echo 0; // failure
    }

    $stmt->close();
    $conn->close();
    exit;
}


