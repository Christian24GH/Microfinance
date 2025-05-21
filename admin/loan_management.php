<?php
session_start();
include '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get loan statistics
$stats_query = "SELECT 
    COUNT(*) as total_loans,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_loans,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_loans,
    SUM(CASE WHEN status = 'defaulted' THEN 1 ELSE 0 END) as defaulted_loans
FROM loans";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get all loans with borrower information
$loans_query = "SELECT l.*, b.name as borrower_name, b.email as borrower_email 
                FROM loans l 
                JOIN borrowers b ON l.borrower_id = b.id 
                ORDER BY l.created_at DESC";
$loans_result = mysqli_query($conn, $loans_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Loan Management</h1>
                    <p class="text-gray-600">Manage and track all loans</p>
                </div>
                <button onclick="showAddLoanModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    New Loan
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-file-invoice-dollar text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Loans</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_loans']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending Loans</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $stats['pending_loans']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Active Loans</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $stats['active_loans']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Defaulted Loans</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $stats['defaulted_loans']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loans Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($loan = mysqli_fetch_assoc($loans_result)): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($loan['borrower_name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($loan['borrower_email']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">$<?php echo number_format($loan['amount'], 2); ?></div>
                                <div class="text-sm text-gray-500"><?php echo $loan['interest_rate']; ?>% APR</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $loan['term_months']; ?> months
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($loan['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewLoan(<?php echo $loan['id']; ?>)" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                <?php if ($loan['status'] === 'pending'): ?>
                                <button onclick="approveLoan(<?php echo $loan['id']; ?>)" 
                                        class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                <button onclick="rejectLoan(<?php echo $loan['id']; ?>)" 
                                        class="text-red-600 hover:text-red-900">Reject</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Loan Modal -->
    <div id="addLoanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Add New Loan</h2>
                    <button onclick="closeModal('addLoanModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="addLoanForm" class="p-6" onsubmit="saveLoan(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Borrower</label>
                        <select name="borrower_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Borrower</option>
                            <?php
                            $borrowers_query = "SELECT * FROM borrowers ORDER BY name";
                            $borrowers_result = mysqli_query($conn, $borrowers_query);
                            while ($borrower = mysqli_fetch_assoc($borrowers_result)) {
                                echo "<option value='{$borrower['id']}'>{$borrower['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loan Amount</label>
                        <input type="number" name="amount" required step="0.01" min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%)</label>
                        <input type="number" name="interest_rate" required step="0.01" min="0" max="100"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Term (months)</label>
                        <input type="number" name="term_months" required min="1"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addLoanModal')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Create Loan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showAddLoanModal() {
        document.getElementById('addLoanModal').classList.remove('hidden');
        document.getElementById('addLoanModal').classList.add('flex');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.getElementById(modalId).classList.remove('flex');
    }

    function saveLoan(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        fetch('api/create_loan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal('addLoanModal');
                form.reset();
                location.reload();
            } else {
                alert(data.message || 'Failed to create loan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to create loan');
        });
    }

    function viewLoan(id) {
        window.location.href = `loan_details.php?id=${id}`;
    }

    function approveLoan(id) {
        if (!confirm('Are you sure you want to approve this loan?')) return;

        fetch('api/approve_loan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to approve loan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to approve loan');
        });
    }

    function rejectLoan(id) {
        const reason = prompt('Please enter rejection reason:');
        if (!reason) return;

        fetch('api/reject_loan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to reject loan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to reject loan');
        });
    }
    </script>
</body>
</html> 