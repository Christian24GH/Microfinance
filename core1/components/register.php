<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Users table
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role_id = 2;

    // Generate unique client_id
    do {
        $client_id = "c250" . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $check_client_id = $conn->prepare("SELECT COUNT(*) FROM client_info WHERE client_id = ?");
        $check_client_id->bind_param("s", $client_id);
        $check_client_id->execute();
        $check_client_id->bind_result($count);
        $check_client_id->fetch();
        $check_client_id->close();
    } while ($count > 0);

    // Client Information
    $first_name = $_POST["first_name"];
    $middle_name = $_POST["middle_name"];
    $last_name = $_POST["last_name"];
    $sex = $_POST["sex"];
    $civil_status = $_POST["civil_status"];
    $birthdate = $_POST["birthdate"];
    $contact_number = $_POST["contact_number"];
    $address = $_POST["address"];
    $barangay = $_POST["barangay"];
    $city = $_POST["city"];
    $province = $_POST["province"];

    $sql_client_info = "INSERT INTO client_info (client_id, first_name, middle_name, last_name, sex, civil_status, birthdate, contact_number, address, barangay, city, province) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_client_info = $conn->prepare($sql_client_info);
    $stmt_client_info->bind_param("ssssssssssss", $client_id, $first_name, $middle_name, $last_name, $sex, $civil_status, $birthdate, $contact_number, $address, $barangay, $city, $province);
    $stmt_client_info->execute();

    $sql_users = "INSERT INTO users (email, password, role_id, client_id) VALUES (?, ?, ?, ?)";
    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param("ssis", $email, $password, $role_id, $client_id);
    $stmt_users->execute();
    $user_id = $conn->insert_id;

    // Client Employment Information
    $employer_name = $_POST["employer_name"];
    $employment_address = $_POST["address"];
    $position = $_POST["position"];

    $sql_employment = "INSERT INTO client_employment (client_emp_id, employer_name, address, position) VALUES (?, ?, ?, ?)";
    $stmt_employment = $conn->prepare($sql_employment);
    $stmt_employment->bind_param("isss", $client_emp_id, $employer_name, $employment_address, $position);
    $stmt_employment->execute();

    // Client Contact References
    $fr_first_name = $_POST["fr_first_name"];
    $fr_last_name = $_POST["fr_last_name"];
    $fr_relationship = $_POST["fr_relationship"];
    $fr_contact_number = $_POST["fr_contact_number"];

    $sr_first_name = $_POST["sr_first_name"];
    $sr_last_name = $_POST["sr_last_name"];
    $sr_relationship = $_POST["sr_relationship"];
    $sr_contact_number = $_POST["sr_contact_number"];

    $sql_references = "INSERT INTO client_references (client_ref_id, fr_first_name, fr_last_name, fr_relationship, fr_contact_number, sr_first_name, sr_last_name, sr_relationship, sr_contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_references = $conn->prepare($sql_references);
    $stmt_references->bind_param("issssssss", $client_ref_id, $fr_first_name, $fr_last_name, $fr_relationship, $fr_contact_number, $sr_first_name, $sr_last_name, $sr_relationship, $sr_contact_number);
    $stmt_references->execute();

    // Client Financial Information
    $source_of_funds = $_POST["source_of_funds"];
    $monthly_income = $_POST["monthly_income"];

    $sql_financial = "INSERT INTO client_financial_info (client_fin_id, source_of_funds, monthly_income) VALUES (?, ?, ?)";
    $stmt_financial = $conn->prepare($sql_financial);
    $stmt_financial->bind_param("iss", $client_fin_id, $source_of_funds, $monthly_income);
    $stmt_financial->execute();

    // Client Document Information
    $docu_type = $_POST["docu_type"];
    $document_one = $POST["document_one"];
    $document_two = $POST["document_two"];

    // Insert into client_documents table
    $sql_documents = "INSERT INTO client_documents (docu_type, document_one, document_two) VALUES (?, ?, ?)";
    $stmt_documents = $conn->prepare($sql_documents);
    $stmt_documents->bind_param("sss", $docu_type, $document_one, $document_two);
    $stmt_documents->execute();

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../resources/css/app.css"rel="stylesheet">
    <link href="../resources/css/sidebar.css"rel="stylesheet">
    <link href="../resources/css/media-query.css"rel="stylesheet">
    <link href="../resources/css/form.css"rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div style="padding: 2rem;">
                    <form id="register" action="" method="POST">
                        <h3>Client Information:</h3> <br>
                        <label for="email" class="form-label">Email address:</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="example@gmail.com" required>
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="" required>
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="" required>
                        <label for="middle_name" class="form-label">Middle Name:</label>
                        <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="" required>
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" required>
                        
                        <label for="sex" class="form-label">Sex:</label>
                        <select name="sex" id="sex" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select><br>
                        <label for="civil_status" class="form-label">Civil Status:</label>
                        <select name="civil_status" id="civil_status" required>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Seperated">Seperated</option>
                            <option value="Widowed">Widowed</option>
                        </select><br>
                        <label for="birthdate" class="form-label">Birthdate:</label>
                        <input type="date" class="form-control" name="birthdate" id="birthdate" placeholder="" required>
                        <label for="contact_number" class="form-label">Contact Number:</label>
                        <input type="number" class="form-control" name="contact_number" id="contact_number" placeholder="" required>
                        
                                                    
                        <label for="address" class="form-label">Address:</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="" required>
                        <label for="barangay" class="form-label">Barangay:</label>
                        <input type="text" class="form-control" name="barangay" id="barangay" placeholder="" required>
                        <label for="city" class="form-label">City:</label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="Quezon City" required>
                        <label for="province" class="form-label">Province:</label>
                        <input type="text" class="form-control" name="province" id="province" placeholder="Metro Manila" required>
                        <br>
                        <h3>Client Employment Information:</h3> <br>
                        <label for="employer_name" class="form-label">Employer Name:</label>
                        <input type="text" class="form-control" name="employer_name" id="employer_name" placeholder="" required>
                        <label for="address" class="form-label">Address:</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="" required>
                        <label for="position" class="form-label">Position:</label>
                        <input type="text" class="form-control" name="position" id="position" placeholder="" required>
                        <br>
                        <h3>Client Contact Reference:</h3> <br>
                        <label for="fr_first_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="fr_first_name" id="fr_first_name" placeholder="" required>
                        <label for="fr_last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="fr_last_name" id="fr_last_name" placeholder="" required>
                        <label for="fr_relationship" class="form-label">Relationship:</label>
                        <select name="fr_relationship" id="fr_relationship" required>
                            <option value="Mother">Mother</option>
                            <option value="Father">Father</option>
                            <option value="Siblings">Siblings</option>
                            <option value="Friends">Friends</option>
                            <option value="Collegue">Collegue</option>
                            <option value="Relatives">Relatives</option>
                        </select><br>
                        <label for="fr_contact_number" class="form-label">Contact Number:</label>
                        <input type="number" class="form-control" name="fr_contact_number" id="fr_contact_number" placeholder="" required>
                        <br>
                        <label for="sr_first_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="sr_first_name" id="sr_first_name" placeholder="" required>
                        <label for="sr_last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="sr_last_name" id="sr_last_name" placeholder="" required>
                        <label for="sr_relationship" class="form-label">Relationship:</label>
                        <select name="sr_relationship" id="sr_relationship" required>
                            <option value="Mother">Mother</option>
                            <option value="Father">Father</option>
                            <option value="Siblings">Siblings</option>
                            <option value="Friends">Friends</option>
                            <option value="Collegue">Collegue</option>
                            <option value="Relatives">Relatives</option>
                        </select><br>
                        <label for="sr_contact_number" class="form-label">Contact Number:</label>
                        <input type="number" class="form-control" name="sr_contact_number" id="sr_contact_number" placeholder="" required>
                        
                        <h3>Client Financial Information</h3>
                        <label for="source_of_funds" class="form-label">Source of funds:</label>
                        <select name="source_of_funds" id="source_of_funds" required>
                            <option value="Employment">Employment</option>
                            <option value="Savings">Savings</option>
                            <option value="Allowance">Allowance</option>
                            <option value="Business">Business</option>
                            <option value="Pension">Pension</option>
                        </select><br>
                        <label for="monthly_income" class="form-label">Monthly Income:</label>
                        <input type="number" class="form-control" name="monthly_income" id="monthly_income" placeholder="" required>
                        
                        <h3>Client Document Information</h3>
                        <label for="docu_type" class="form-label">Valid ID:</label>
                        <select name="docu_type" id="docu_type" required>
                            <option value="Driver's License">Driver's License</option>
                            <option value="Passport">Passport</option>
                            <option value="SSS">SSS</option>
                            <option value="UMID">UMID</option>
                            <option value="PhilID">PhilID</option>
                        </select><br>
                        <label for="document_one" class="form-label">Front ID Picture:</label>
                        <input type="file" class="form-control" name="document_one" id="document_one" accept="image/png, image/jpeg" required>
                        
                        <label for="document_two" class="form-label">Back ID Picture:</label>
                        <input type="file" class="form-control" name="document_two" id="document_two" accept="image/png, image/jpeg" required>
                        <br>
                        <button type="submit" class="btn w-100 rounded-pill">Register</button>
                        <button onclick="window.location.href='../index.php'" class="btn w-100 rounded-pill" onclick="">Back</button>
                    
                    </form>
                </div>
</body>
</html>