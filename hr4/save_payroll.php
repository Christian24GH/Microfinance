<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// Include DB connection
require_once 'connect.php';

// Validate required fields
$required_fields = ['employee_id', 'employee_name', 'pay_period', 'gross_pay', 'deductions'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(["status" => "error", "message" => "Missing required field: $field"]);
        exit;
    }
}

// Sanitize and assign values
$employee_id = trim($_POST['employee_id']);
$employee_name = trim($_POST['employee_name']);
$pay_period = $_POST['pay_period'];
$gross_pay = (float)$_POST['gross_pay'];
$deductions = (float)$_POST['deductions'];
$net_pay = $gross_pay - $deductions;

// Prepare and execute insert query
$sql = "INSERT INTO payroll (employee_id, employee_name, pay_period, gross_pay, deductions, net_pay) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssdd", $employee_id, $employee_name, $pay_period, $gross_pay, $deductions, $net_pay);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Payroll record saved successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
