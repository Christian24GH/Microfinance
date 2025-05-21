@extends('layouts.app')

@section('title', 'Approval Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-4 px-4">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Approval Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Review and approve claim submissions</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="refreshClaims()"
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                        <p class="text-2xl font-bold text-gray-900" id="pendingCount">0</p>
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
                        <p class="text-sm font-medium text-gray-600">Approved Today</p>
                        <p class="text-2xl font-bold text-gray-900" id="approvedToday">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rejected Today</p>
                        <p class="text-2xl font-bold text-gray-900" id="rejectedToday">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claims Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="claimsBody" class="bg-white divide-y divide-gray-200">
                        <!-- Claims will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- View Claim Modal -->
        <div id="viewClaimModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="viewClaimModalTitle" tabindex="-1">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 id="viewClaimModalTitle" class="text-xl font-semibold text-gray-900">Claim Details</h2>
                            <p class="text-sm text-gray-600 mt-1">Review claim information</p>
                        </div>
                        <button onclick="closeModal('viewClaimModal')" class="text-gray-400 hover:text-gray-600" aria-label="Close Claim Details Modal">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="claimDetails" class="mb-6">
                        <!-- Claim details will be loaded here -->
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                                </tr>
                            </thead>
                            <tbody id="claimItemsBody" class="bg-white divide-y divide-gray-200">
                                <!-- Claim items will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button onclick="closeModal('viewClaimModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Close</button>
                        <button onclick="approveClaim()" id="approveBtn" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">Approve</button>
                        <button onclick="rejectClaim()" id="rejectBtn" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">Reject</button>
                        <button onclick="reimburseClaim()" id="reimburseBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg" style="display:none;">Mark as Reimbursed</button>
                    </div>
                </div>
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
let currentClaimId = null;

// Initialize real-time updates
function initializeRealTimeUpdates() {
    Echo.private('claims.approvals')
        .listen('ClaimStatusUpdated', (e) => {
            refreshClaims();
            updateStats();
            showToast('Claim status updated', 'success');
        });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeRealTimeUpdates();
    refreshClaims();
    updateStats();
});

function refreshClaims() {
    fetch('/api/claims/pending')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('claimsBody');
                tbody.innerHTML = '';
                data.claims.forEach(claim => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${claim.employee.name}</div>
                            <div class="text-sm text-gray-500">${claim.employee.employee_id}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${claim.claim_type}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₱${claim.total_amount.toFixed(2)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                ${claim.status === 'reimbursed' ? 'bg-blue-100 text-blue-800' :
                                  claim.status === 'approved' ? 'bg-green-100 text-green-800' :
                                  claim.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                  'bg-red-100 text-red-800'}">
                                ${claim.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${claim.current_stage}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewClaim(${claim.id})" class="text-blue-600 hover:text-blue-900">View</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load claims', 'error');
        });
}

function updateStats() {
    fetch('/api/claims/stats')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('pendingCount').textContent = data.pending_count;
                document.getElementById('approvedToday').textContent = data.approved_today;
                document.getElementById('rejectedToday').textContent = data.rejected_today;
            }
        })
        .catch(error => {
            console.error('Error updating stats:', error);
        });
}

function viewClaim(id) {
    currentClaimId = id;
    fetch(`/api/claims/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const claim = data.claim;
                document.getElementById('claimDetails').innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Employee</p>
                            <p class="text-sm text-gray-900">${claim.employee.name}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Claim Type</p>
                            <p class="text-sm text-gray-900">${claim.claim_type}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Amount</p>
                            <p class="text-sm text-gray-900">₱${claim.total_amount.toFixed(2)}</p>
                        </div>
                    </div>
                `;

                const tbody = document.getElementById('claimItemsBody');
                tbody.innerHTML = '';
                claim.items.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.description}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱${item.amount.toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="${item.receipt_url}" target="_blank" class="text-blue-600 hover:text-blue-900">View Receipt</a>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Show/hide action buttons based on status
                document.getElementById('approveBtn').style.display = claim.status === 'pending' ? '' : 'none';
                document.getElementById('rejectBtn').style.display = claim.status === 'pending' ? '' : 'none';
                document.getElementById('reimburseBtn').style.display = claim.status === 'approved' ? '' : 'none';

                document.getElementById('viewClaimModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load claim details', 'error');
        });
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function approveClaim() {
    if (!currentClaimId) return;

    fetch(`/api/claims/${currentClaimId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('viewClaimModal');
            refreshClaims();
            showToast('Claim approved successfully', 'success');
        } else {
            showToast(data.message || 'Failed to approve claim', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to approve claim', 'error');
    });
}

function rejectClaim() {
    if (!currentClaimId) return;

    const reason = prompt('Please enter rejection reason:');
    if (!reason) return;

    fetch(`/api/claims/${currentClaimId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('viewClaimModal');
            refreshClaims();
            showToast('Claim rejected successfully', 'success');
        } else {
            showToast(data.message || 'Failed to reject claim', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to reject claim', 'error');
    });
}

function reimburseClaim() {
    if (!currentClaimId) return;
    fetch(`/api/claims/${currentClaimId}/reimburse`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('viewClaimModal');
            refreshClaims();
            showToast('Claim marked as reimbursed', 'success');
        } else {
            showToast(data.message || 'Failed to reimburse claim', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to reimburse claim', 'error');
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
