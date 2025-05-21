@extends('layouts.app')

@section('title', 'Payment Processing')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-4 px-4">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Payment Processing</h1>
                    <p class="text-sm text-gray-600 mt-1">Process and track claim reimbursements</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="refreshPayments()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition-all duration-200 shadow-sm hover:shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                        <p class="text-2xl font-bold text-gray-900" id="pendingPayments">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Processed Today</p>
                        <p class="text-2xl font-bold text-gray-900" id="processedToday">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Amount Today</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalAmountToday">₱0.00</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Monthly Total</p>
                        <p class="text-2xl font-bold text-gray-900" id="monthlyTotal">₱0.00</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="paymentsBody" class="bg-white divide-y divide-gray-200">
                        <!-- Payments will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Process Payment Modal -->
        <div id="processPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="processPaymentModalTitle" tabindex="-1">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 id="processPaymentModalTitle" class="text-xl font-semibold text-gray-900">Process Payment</h2>
                            <p class="text-sm text-gray-600 mt-1">Review and process claim payment</p>
                        </div>
                        <button onclick="closeModal('processPaymentModal')" class="text-gray-400 hover:text-gray-600" aria-label="Close Process Payment Modal">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <form id="processPaymentForm" class="p-6" onsubmit="processPayment(event)">
                    <input type="hidden" id="payment_id" name="payment_id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select id="payment_method" name="payment_method" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Select Payment Method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                            </select>
                        </div>
                        <div>
                            <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                            <input type="text" id="reference_number" name="reference_number" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                            <input type="date" id="payment_date" name="payment_date" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('processPaymentModal')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Process Payment</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
let currentPaymentId = null;

// Initialize real-time updates
function initializeRealTimeUpdates() {
    Echo.private('payments')
        .listen('PaymentStatusUpdated', (e) => {
            refreshPayments();
            updateStats();
            showToast('Payment status updated', 'success');
        });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeRealTimeUpdates();
    refreshPayments();
    updateStats();
});

function refreshPayments() {
    fetch('/api/payments/pending')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('paymentsBody');
                tbody.innerHTML = '';
                data.payments.forEach(payment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${payment.employee.name}</div>
                            <div class="text-sm text-gray-500">${payment.employee.employee_id}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${payment.claim_type}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₱${payment.amount.toFixed(2)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                ${payment.status === 'processed' ? 'bg-green-100 text-green-800' :
                                  payment.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                  'bg-red-100 text-red-800'}">
                                ${payment.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${payment.date}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showProcessPayment(${payment.id})" class="text-blue-600 hover:text-blue-900">Process</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load payments', 'error');
        });
}

function updateStats() {
    fetch('/api/payments/stats')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('pendingPayments').textContent = data.pending_count;
                document.getElementById('processedToday').textContent = data.processed_today;
                document.getElementById('totalAmountToday').textContent = '₱' + data.total_amount_today.toFixed(2);
                document.getElementById('monthlyTotal').textContent = '₱' + data.monthly_total.toFixed(2);
            }
        })
        .catch(error => {
            console.error('Error updating stats:', error);
        });
}

function showProcessPayment(id) {
    currentPaymentId = id;
    fetch(`/api/payments/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('payment_id').value = id;
                document.getElementById('payment_date').value = new Date().toISOString().split('T')[0];
                document.getElementById('processPaymentModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load payment details', 'error');
        });
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function processPayment(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    fetch(`/api/payments/${currentPaymentId}/process`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('processPaymentModal');
            refreshPayments();
            showToast('Payment processed successfully', 'success');
        } else {
            showToast(data.message || 'Failed to process payment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to process payment', 'error');
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
