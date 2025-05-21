<?php
session_start();
include '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['borrower_id']) || !isset($data['amount']) || !isset($data['interest_rate']) || !isset($data['term_months'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Insert loan
    $stmt = $conn->prepare("INSERT INTO loans (borrower_id, amount, interest_rate, term_months, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iddi", $data['borrower_id'], $data['amount'], $data['interest_rate'], $data['term_months']);
    $stmt->execute();
    $loan_id = $conn->insert_id;

    // Calculate payment schedule
    $monthly_payment = calculateMonthlyPayment($data['amount'], $data['interest_rate'], $data['term_months']);
    $current_date = new DateTime();
    
    for ($i = 1; $i <= $data['term_months']; $i++) {
        $due_date = clone $current_date;
        $due_date->modify("+$i months");
        
        $stmt = $conn->prepare("INSERT INTO payment_schedule (loan_id, due_date, amount, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("isd", $loan_id, $due_date->format('Y-m-d'), $monthly_payment);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Loan created successfully',
        'loan_id' => $loan_id
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to create loan: ' . $e->getMessage()]);
}

// Function to calculate monthly payment using the loan amortization formula
function calculateMonthlyPayment($principal, $annual_rate, $term_months) {
    $monthly_rate = $annual_rate / 100 / 12;
    $numerator = $principal * $monthly_rate * pow(1 + $monthly_rate, $term_months);
    $denominator = pow(1 + $monthly_rate, $term_months) - 1;
    return round($numerator / $denominator, 2);
}
?> 