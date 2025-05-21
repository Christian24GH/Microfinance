<?php
session_start();
include '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Get loan ID from query parameters
$loan_id = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;

if (!$loan_id) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Loan ID is required']);
    exit();
}

try {
    // Get payment schedule with payment status
    $stmt = $conn->prepare("
        SELECT 
            ps.*,
            p.id as payment_id,
            p.amount as paid_amount,
            p.payment_date,
            p.payment_method,
            p.notes
        FROM payment_schedule ps
        LEFT JOIN payments p ON ps.id = p.payment_schedule_id
        WHERE ps.loan_id = ?
        ORDER BY ps.due_date ASC
    ");
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedule = [];
    while ($row = $result->fetch_assoc()) {
        $schedule[] = [
            'id' => $row['id'],
            'due_date' => $row['due_date'],
            'amount' => $row['amount'],
            'status' => $row['status'],
            'payment' => $row['payment_id'] ? [
                'id' => $row['payment_id'],
                'amount' => $row['paid_amount'],
                'payment_date' => $row['payment_date'],
                'payment_method' => $row['payment_method'],
                'notes' => $row['notes']
            ] : null
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $schedule
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch payment schedule: ' . $e->getMessage()]);
}
?> 