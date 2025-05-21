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
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing loan ID']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Update loan status
    $stmt = $conn->prepare("UPDATE loans SET status = 'active', approved_at = NOW(), approved_by = ? WHERE id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $_SESSION['admin_id'], $data['id']);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Loan not found or already processed');
    }

    // Update payment schedule status
    $stmt = $conn->prepare("UPDATE payment_schedule SET status = 'pending' WHERE loan_id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Loan approved successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to approve loan: ' . $e->getMessage()]);
}
?> 