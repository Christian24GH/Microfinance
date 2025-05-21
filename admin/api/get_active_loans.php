<?php
session_start();
include '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

try {
    // Get active loans with borrower information and payment details
    $stmt = $conn->prepare("
        SELECT 
            l.id,
            l.loan_number,
            l.amount,
            l.interest_rate,
            l.term_months,
            l.status,
            l.created_at,
            CONCAT(b.first_name, ' ', b.last_name) as borrower_name,
            b.phone as borrower_phone,
            b.email as borrower_email,
            (
                SELECT COUNT(*) 
                FROM payment_schedule ps 
                WHERE ps.loan_id = l.id AND ps.status = 'paid'
            ) as payments_made,
            (
                SELECT COUNT(*) 
                FROM payment_schedule ps 
                WHERE ps.loan_id = l.id
            ) as total_payments,
            (
                SELECT SUM(ps.amount) 
                FROM payment_schedule ps 
                WHERE ps.loan_id = l.id AND ps.status = 'paid'
            ) as total_paid,
            (
                SELECT MIN(ps.due_date) 
                FROM payment_schedule ps 
                WHERE ps.loan_id = l.id AND ps.status = 'pending'
            ) as next_payment_date
        FROM loans l
        JOIN borrowers b ON l.borrower_id = b.id
        WHERE l.status IN ('active', 'pending')
        ORDER BY l.created_at DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $loans = [];
    while ($row = $result->fetch_assoc()) {
        // Calculate remaining balance
        $remaining_balance = $row['amount'] - ($row['total_paid'] ?? 0);
        
        // Calculate progress percentage
        $progress = $row['total_payments'] > 0 ? 
            round(($row['payments_made'] / $row['total_payments']) * 100) : 0;
        
        $loans[] = [
            'id' => $row['id'],
            'loan_number' => $row['loan_number'],
            'amount' => number_format($row['amount'], 2),
            'interest_rate' => $row['interest_rate'],
            'term_months' => $row['term_months'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'borrower_name' => $row['borrower_name'],
            'borrower_phone' => $row['borrower_phone'],
            'borrower_email' => $row['borrower_email'],
            'payments_made' => $row['payments_made'],
            'total_payments' => $row['total_payments'],
            'total_paid' => number_format($row['total_paid'] ?? 0, 2),
            'remaining_balance' => number_format($remaining_balance, 2),
            'next_payment_date' => $row['next_payment_date'],
            'progress' => $progress
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $loans
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Failed to fetch loans: ' . $e->getMessage()
    ]);
}
?> 