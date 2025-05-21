<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Payments Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Loan Payments Management</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                        <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <i class="fas fa-file-invoice-dollar text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Active Loans</p>
                            <p class="text-2xl font-bold text-gray-900" id="totalActiveLoans">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Payments Today</p>
                            <p class="text-2xl font-bold text-gray-900" id="paymentsToday">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                            <i class="fas fa-clock text-yellow-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                            <p class="text-2xl font-bold text-gray-900" id="pendingApprovals">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <i class="fas fa-chart-line text-purple-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Collection Rate</p>
                            <p class="text-2xl font-bold text-gray-900" id="collectionRate">0%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Record Payment</h2>
                <form id="paymentForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="loan_id" class="block text-sm font-medium text-gray-700 mb-2">Select Loan</label>
                        <select id="loan_id" name="loan_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a loan...</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                        <input type="date" id="payment_date" name="payment_date" required 
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₱</span>
                            <input type="number" id="amount" name="amount" required step="0.01" min="0"
                                   class="w-full pl-8 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <select id="payment_method" name="payment_method" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <input type="text" id="notes" name="notes" 
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Optional notes about the payment">
                    </div>
                    <div class="md:col-span-3">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Active Loans Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Active Loans</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="loansTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Loans will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Payment Schedule Modal -->
    <div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Payment Schedule</h2>
                    <button onclick="closeModal('scheduleModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="scheduleDetails" class="mb-6">
                    <!-- Schedule details will be loaded here -->
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Schedule entries will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load active loans on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadActiveLoans();
            updateStats();
        });

        // Load active loans
        function loadActiveLoans() {
            fetch('api/get_active_loans.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const tbody = document.getElementById('loansTableBody');
                        const loanSelect = document.getElementById('loan_id');
                        
                        // Clear existing options except the first one
                        while (loanSelect.options.length > 1) {
                            loanSelect.remove(1);
                        }
                        
                        tbody.innerHTML = '';
                        data.loans.forEach(loan => {
                            // Add to table
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${loan.loan_number}</div>
                                    <div class="text-sm text-gray-500">${loan.interest_rate}% / ${loan.term_months} months</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${loan.borrower_name}</div>
                                    <div class="text-sm text-gray-500">${loan.borrower_phone}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">₱${loan.amount}</div>
                                    <div class="text-sm text-gray-500">Remaining: ₱${loan.remaining_balance}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${loan.progress_percentage}%"></div>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">${loan.progress_percentage}%</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${loan.next_payment_date}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewSchedule(${loan.id})" class="text-blue-600 hover:text-blue-900">View Schedule</button>
                                </td>
                            `;
                            tbody.appendChild(row);

                            // Add to select dropdown
                            const option = document.createElement('option');
                            option.value = loan.id;
                            option.textContent = `${loan.loan_number} - ${loan.borrower_name} (₱${loan.amount})`;
                            loanSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load loans', 'error');
                });
        }

        // View payment schedule
        function viewSchedule(loanId) {
            fetch(`api/get_payment_schedule.php?loan_id=${loanId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const schedule = data.schedule;
                        document.getElementById('scheduleDetails').innerHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Loan Number</p>
                                    <p class="text-sm text-gray-900">${schedule.loan_number}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Borrower</p>
                                    <p class="text-sm text-gray-900">${schedule.borrower_name}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Amount</p>
                                    <p class="text-sm text-gray-900">₱${schedule.total_amount}</p>
                                </div>
                            </div>
                        `;

                        const tbody = document.getElementById('scheduleTableBody');
                        tbody.innerHTML = '';
                        schedule.payments.forEach(payment => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.due_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱${payment.amount}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        ${payment.status === 'paid' ? 'bg-green-100 text-green-800' :
                                          payment.status === 'overdue' ? 'bg-red-100 text-red-800' :
                                          'bg-yellow-100 text-yellow-800'}">
                                        ${payment.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${payment.payment_date || 'N/A'}
                                </td>
                            `;
                            tbody.appendChild(row);
                        });

                        document.getElementById('scheduleModal').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load payment schedule', 'error');
                });
        }

        // Handle payment form submission
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('api/record_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast('Payment recorded successfully', 'success');
                    this.reset();
                    loadActiveLoans();
                    updateStats();
                } else {
                    showToast(data.message || 'Failed to record payment', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to record payment', 'error');
            });
        });

        // Update stats
        function updateStats() {
            fetch('api/get_stats.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('totalActiveLoans').textContent = data.total_active_loans;
                        document.getElementById('paymentsToday').textContent = '₱' + data.payments_today;
                        document.getElementById('pendingApprovals').textContent = data.pending_approvals;
                        document.getElementById('collectionRate').textContent = data.collection_rate + '%';
                    }
                })
                .catch(error => {
                    console.error('Error updating stats:', error);
                });
        }

        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } shadow-lg z-50`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</body>
</html> 
</html> 