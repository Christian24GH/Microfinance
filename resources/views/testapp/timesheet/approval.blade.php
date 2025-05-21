@extends('layouts.app')

@section('title', 'Timesheet Approval')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-4 px-4">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Timesheet Approval</h1>
                    <p class="text-sm text-gray-600 mt-1">Review and approve employee timesheets</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="refreshTimesheets()"
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
                        <p class="text-sm font-medium text-gray-600">{{ __('Pending Approvals') }}</p>
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
                        <p class="text-sm font-medium text-gray-600">{{ __('Approved Today') }}</p>
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
                        <p class="text-sm font-medium text-gray-600">{{ __('Rejected Today') }}</p>
                        <p class="text-2xl font-bold text-gray-900" id="rejectedToday">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timesheets Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employee') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Period') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Hours') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Current Stage') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="timesheetsBody" class="bg-white divide-y divide-gray-200">
                        <!-- Timesheets will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- View Timesheet Modal -->
        <div id="viewTimesheetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="viewTimesheetModalTitle" tabindex="-1">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 id="viewTimesheetModalTitle" class="text-xl font-semibold text-gray-900">{{ __('Timesheet Details') }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Review timesheet entries') }}</p>
                        </div>
                        <button onclick="closeModal('viewTimesheetModal')" class="text-gray-400 hover:text-gray-600" aria-label="{{ __('Close Timesheet Details Modal') }}">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="timesheetDetails" class="mb-6">
                        <!-- Timesheet details will be loaded here -->
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Time In') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Time Out') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Hours') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="timesheetEntriesBody" class="bg-white divide-y divide-gray-200">
                                <!-- Time entries will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button onclick="closeModal('viewTimesheetModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">{{ __('Close') }}</button>
                        <button dusk="approve-btn" onclick="approveTimesheet()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">{{ __('Approve') }}</button>
                        <button dusk="reject-btn" onclick="rejectTimesheet()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">{{ __('Reject') }}</button>
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
let currentTimesheetId = null;

// Initialize real-time updates
function initializeRealTimeUpdates() {
    Echo.private('timesheet.approvals')
        .listen('TimesheetStatusUpdated', (e) => {
            refreshTimesheets();
            updateStats();
            showToast('{{ __('Timesheet status updated') }}', 'success');
        });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeRealTimeUpdates();
    refreshTimesheets();
    updateStats();
});

function refreshTimesheets() {
    fetch('/api/timesheets/pending')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('timesheetsBody');
                tbody.innerHTML = '';
                data.timesheets.forEach(timesheet => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${timesheet.employee.name}</div>
                            <div class="text-sm text-gray-500">${timesheet.employee.employee_id}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${timesheet.start_date} - ${timesheet.end_date}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${timesheet.total_hours}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                ${timesheet.status === 'approved' ? 'bg-green-100 text-green-800' :
                                  timesheet.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                  'bg-red-100 text-red-800'}">
                                ${timesheet.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${timesheet.current_stage}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button dusk="view-timesheet-${timesheet.id}" onclick="viewTimesheet(${timesheet.id})" class="text-blue-600 hover:text-blue-900 mr-3">{{ __('View') }}</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('{{ __('Failed to load timesheets') }}', 'error');
        });
}

function updateStats() {
    fetch('/api/timesheets/stats')
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

function viewTimesheet(id) {
    currentTimesheetId = id;
    fetch(`/api/timesheets/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const timesheet = data.timesheet;
                document.getElementById('timesheetDetails').innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Employee') }}</p>
                            <p class="text-sm text-gray-900">${timesheet.employee.name}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Period') }}</p>
                            <p class="text-sm text-gray-900">${timesheet.start_date} - ${timesheet.end_date}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Total Hours') }}</p>
                            <p class="text-sm text-gray-900">${timesheet.total_hours}</p>
                        </div>
                    </div>
                `;

                const tbody = document.getElementById('timesheetEntriesBody');
                tbody.innerHTML = '';
                timesheet.entries.forEach(entry => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.time_in}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.time_out || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.total_hours || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                ${entry.status === 'approved' ? 'bg-green-100 text-green-800' :
                                  entry.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                  'bg-red-100 text-red-800'}">
                                ${entry.status}
                            </span>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                document.getElementById('viewTimesheetModal').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('viewTimesheetModal').focus();
                }, 100);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('{{ __('Failed to load timesheet details') }}', 'error');
        });
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function approveTimesheet() {
    if (!currentTimesheetId) return;

    fetch(`/api/timesheets/${currentTimesheetId}/approve`, {
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
            closeModal('viewTimesheetModal');
            refreshTimesheets();
            updateStats();
            showToast('{{ __('Timesheet approved successfully') }}', 'success');
        } else {
            showToast(data.message || '{{ __('Failed to approve timesheet') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('{{ __('Failed to approve timesheet') }}', 'error');
    });
}

function rejectTimesheet() {
    if (!currentTimesheetId) return;

    const reason = prompt('{{ __('Please enter rejection reason') }}:');
    if (!reason) return;

    fetch(`/api/timesheets/${currentTimesheetId}/reject`, {
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
            closeModal('viewTimesheetModal');
            refreshTimesheets();
            updateStats();
            showToast('{{ __('Timesheet rejected successfully') }}', 'success');
        } else {
            showToast(data.message || '{{ __('Failed to reject timesheet') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('{{ __('Failed to reject timesheet') }}', 'error');
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } shadow-lg z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Add loading spinner for AJAX actions
function showLoadingSpinner() {
    let spinner = document.getElementById('loadingSpinner');
    if (!spinner) {
        spinner = document.createElement('div');
        spinner.id = 'loadingSpinner';
        spinner.className = 'fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50';
        spinner.innerHTML = `<div class='loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-24 w-24'></div>`;
        document.body.appendChild(spinner);
    }
}
function hideLoadingSpinner() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.remove();
}
</script>
<style>
.loader {
  border-top-color: #3498db;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
@endsection
