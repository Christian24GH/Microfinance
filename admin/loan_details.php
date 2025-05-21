<?php
session_start();
include '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get loan ID from URL
if (!isset($_GET['id'])) {
    header('Location: loan_management.php');
    exit();
}

$loan_id = intval($_GET['id']);

// Get loan details with borrower information
$loan_query = "SELECT l.*, b.name as borrower_name, b.email as borrower_email, b.phone as borrower_phone,
                      u1.name as approved_by_name, u2.name as rejected_by_name
               FROM loans l 
               JOIN borrowers b ON l.borrower_id = b.id 
               LEFT JOIN users u1 ON l.approved_by = u1.id
               LEFT JOIN users u2 ON l.rejected_by = u2.id
               WHERE l.id = ?";
$stmt = $conn->prepare($loan_query);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$loan = $stmt->get_result()->fetch_assoc();

if (!$loan) {
    header('Location: loan_management.php');
    exit();
}

// Get payment schedule
$schedule_query = "SELECT * FROM payment_schedule WHERE loan_id = ? ORDER BY due_date";
$stmt = $conn->prepare($schedule_query);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$payment_schedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get payment history
$payments_query = "SELECT p.*, ps.due_date 
                  FROM payments p 
                  JOIN payment_schedule ps ON p.payment_schedule_id = ps.id 
                  WHERE p.loan_id = ? 
                  ORDER BY p.payment_date DESC";
$stmt = $conn->prepare($payments_query);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$payments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Loan Details</h1>
                    <p class="text-gray-600">View and manage loan information</p>
                </div>
                <a href="loan_management.php" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Loans
                </a>
            </div>
        </div>

        <!-- Loan Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Loan Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Loan Amount</p>
                        <p class="text-lg font-medium text-gray-900">$<?php echo number_format($loan['amount'], 2); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Interest Rate</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo $loan['interest_rate']; ?>% APR</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Term</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo $loan['term_months']; ?> months</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-2 py-1 text-sm font-medium rounded-full
                            <?php
                            switch($loan['status']) {
                                case 'pending':
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'approved':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'rejected':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                case 'active':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                case 'completed':
                                    echo 'bg-gray-100 text-gray-800';
                                    break;
                                case 'defaulted':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                            }
                            ?>">
                            <?php echo ucfirst($loan['status']); ?>
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created Date</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo date('M d, Y', strtotime($loan['created_at'])); ?></p>
                    </div>
                    <?php if ($loan['approved_at']): ?>
                    <div>
                        <p class="text-sm text-gray-600">Approved Date</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo date('M d, Y', strtotime($loan['approved_at'])); ?></p>
                        <p class="text-sm text-gray-500">by <?php echo $loan['approved_by_name']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($loan['rejected_at']): ?>
                    <div>
                        <p class="text-sm text-gray-600">Rejected Date</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo date('M d, Y', strtotime($loan['rejected_at'])); ?></p>
                        <p class="text-sm text-gray-500">by <?php echo $loan['rejected_by_name']; ?></p>
                        <p class="text-sm text-gray-500">Reason: <?php echo $loan['rejection_reason']; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Borrower Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($loan['borrower_name']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($loan['borrower_email']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($loan['borrower_phone']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Schedule -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Payment Schedule</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($payment_schedule as $payment): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('M d, Y', strtotime($payment['due_date'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $<?php echo number_format($payment['amount'], 2); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php
                                    switch($payment['status']) {
                                        case 'pending':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'paid':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'overdue':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                        case 'cancelled':
                                            echo 'bg-gray-100 text-gray-800';
                                            break;
                                    }
                                    ?>">
                                    <?php echo ucfirst($payment['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if ($payment['status'] === 'pending' && $loan['status'] === 'active'): ?>
                                <button onclick="recordPayment(<?php echo $payment['id']; ?>)"
                                        class="text-blue-600 hover:text-blue-900">Record Payment</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment History -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Payment History</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('M d, Y', strtotime($payment['payment_date'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo date('M d, Y', strtotime($payment['due_date'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $<?php echo number_format($payment['amount'], 2); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo ucfirst(str_replace('_', ' ', $payment['payment_method'])); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?php echo htmlspecialchars($payment['notes'] ?? ''); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div id="recordPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Record Payment</h2>
                    <button onclick="closeModal('recordPaymentModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="recordPaymentForm" class="p-6" onsubmit="savePayment(event)">
                <input type="hidden" id="payment_schedule_id" name="payment_schedule_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                        <input type="date" name="payment_date" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <input type="number" name="amount" required step="0.01" min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <select name="payment_method" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('recordPaymentModal')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function recordPayment(paymentScheduleId) {
        document.getElementById('payment_schedule_id').value = paymentScheduleId;
        document.getElementById('recordPaymentModal').classList.remove('hidden');
        document.getElementById('recordPaymentModal').classList.add('flex');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.getElementById(modalId).classList.remove('flex');
    }

    function savePayment(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.loan_id = <?php echo $loan_id; ?>;

        fetch('api/record_payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                closeModal('recordPaymentModal');
                form.reset();
                location.reload();
            } else {
                alert(data.message || 'Failed to record payment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to record payment');
        });
    }
    </script>
</body>
</html> 