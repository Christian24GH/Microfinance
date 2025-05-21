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
if (!isset($data['loan_id']) || !isset($data['payment_schedule_id']) || !isset($data['amount']) || 
    !isset($data['payment_date']) || !isset($data['payment_method'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Get payment schedule details
    $stmt = $conn->prepare("SELECT * FROM payment_schedule WHERE id = ? AND loan_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $data['payment_schedule_id'], $data['loan_id']);
    $stmt->execute();
    $payment_schedule = $stmt->get_result()->fetch_assoc();

    if (!$payment_schedule) {
        throw new Exception('Invalid payment schedule or already paid');
    }

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payments (loan_id, payment_schedule_id, amount, payment_date, payment_method, notes) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidsss", $data['loan_id'], $data['payment_schedule_id'], $data['amount'], 
                      $data['payment_date'], $data['payment_method'], $data['notes']);
    $stmt->execute();

    // Update payment schedule status
    $stmt = $conn->prepare("UPDATE payment_schedule SET status = 'paid', paid_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $data['payment_schedule_id']);
    $stmt->execute();

    // Check if all payments are completed
    $stmt = $conn->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid 
                           FROM payment_schedule WHERE loan_id = ?");
    $stmt->bind_param("i", $data['loan_id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // If all payments are completed, update loan status
    if ($result['total'] === $result['paid']) {
        $stmt = $conn->prepare("UPDATE loans SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $data['loan_id']);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Payment recorded successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to record payment: ' . $e->getMessage()]);
}
?> 