@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Overtime Records</h1>
        <button onclick="openAddRecordModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            Add New Record
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label for="employeeFilter" class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                <select id="employeeFilter" onchange="applyFilters()"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Employees</option>
                    <!-- Employees will be loaded here -->
                </select>
            </div>
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" onchange="applyFilters()"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label for="dateFromFilter" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" id="dateFromFilter" onchange="applyFilters()"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="dateToFilter" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" id="dateToFilter" onchange="applyFilters()"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Records List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compensation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="recordsList">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Record Modal -->
    <div id="recordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 id="modalTitle" class="text-xl font-semibold text-gray-900">Add New Record</h2>
                    <button onclick="closeRecordModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <form id="recordForm" class="p-6" onsubmit="saveRecord(event)">
                <input type="hidden" id="recordUuid">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                        <select id="employee_id" name="employee_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <!-- Employees will be loaded here -->
                        </select>
                    </div>
                    <div>
                        <label for="policy_id" class="block text-sm font-medium text-gray-700 mb-2">Policy</label>
                        <select id="policy_id" name="policy_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <!-- Policies will be loaded here -->
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" id="date" name="date" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                        <input type="time" id="start_time" name="start_time" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                        <input type="time" id="end_time" name="end_time" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                        <textarea id="reason" name="reason" rows="2" required
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="tasks_completed" class="block text-sm font-medium text-gray-700 mb-2">Tasks Completed</label>
                        <textarea id="tasks_completed" name="tasks_completed" rows="3" required
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="closeRecordModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Record Modal -->
    <div id="viewRecordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Overtime Record Details</h2>
                    <button onclick="closeViewRecordModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div id="recordDetails" class="p-6">
                <!-- Record details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
let records = [];
let employees = [];
let policies = [];

function loadEmployees() {
    fetch('/api/employees')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                employees = data.employees;
                const employeeOptions = employees.map(emp =>
                    `<option value="${emp.id}">${emp.name}</option>`
                ).join('');

                document.getElementById('employeeFilter').innerHTML =
                    '<option value="">All Employees</option>' + employeeOptions;
                document.getElementById('employee_id').innerHTML = employeeOptions;
            }
        })
        .catch(error => {
            console.error('Error loading employees:', error);
            showToast('Failed to load employees', 'error');
        });
}

function loadPolicies() {
    fetch('/api/overtime-policies')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                policies = data.policies;
                const policyOptions = policies.map(policy =>
                    `<option value="${policy.id}">${policy.name}</option>`
                ).join('');

                document.getElementById('policy_id').innerHTML = policyOptions;
            }
        })
        .catch(error => {
            console.error('Error loading policies:', error);
            showToast('Failed to load policies', 'error');
        });
}

function loadRecords() {
    const filters = {
        employee_id: document.getElementById('employeeFilter').value,
        status: document.getElementById('statusFilter').value,
        date_from: document.getElementById('dateFromFilter').value,
        date_to: document.getElementById('dateToFilter').value
    };

    const queryString = new URLSearchParams(filters).toString();
    fetch(`/api/overtime-records?${queryString}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                records = data.records;
                renderRecords();
            }
        })
        .catch(error => {
            console.error('Error loading records:', error);
            showToast('Failed to load records', 'error');
        });
}

function renderRecords() {
    const tbody = document.getElementById('recordsList');
    tbody.innerHTML = records.map(record => {
        const employee = employees.find(emp => emp.id === record.employee_id);
        const policy = policies.find(pol => pol.id === record.policy_id);

        return `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${employee ? employee.name : 'Unknown'}</div>
                    <div class="text-sm text-gray-500">${policy ? policy.name : 'Unknown Policy'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${record.date}</div>
                    <div class="text-sm text-gray-500">${record.start_time} - ${record.end_time}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${record.total_hours} hours</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">$${record.compensation_amount.toFixed(2)}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                        record.status === 'approved' ? 'bg-green-100 text-green-800' :
                        record.status === 'rejected' ? 'bg-red-100 text-red-800' :
                        'bg-yellow-100 text-yellow-800'
                    }">
                        ${record.status.charAt(0).toUpperCase() + record.status.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="viewRecord('${record.uuid}')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                    ${record.status === 'pending' ? `
                        <button onclick="editRecord('${record.uuid}')" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</button>
                        <button onclick="deleteRecord('${record.uuid}')" class="text-red-600 hover:text-red-900">Delete</button>
                    ` : ''}
                </td>
            </tr>
        `;
    }).join('');
}

function openAddRecordModal() {
    document.getElementById('modalTitle').textContent = 'Add New Record';
    document.getElementById('recordForm').reset();
    document.getElementById('recordUuid').value = '';
    document.getElementById('recordModal').classList.remove('hidden');
}

function closeRecordModal() {
    document.getElementById('recordModal').classList.add('hidden');
}

function viewRecord(uuid) {
    const record = records.find(r => r.uuid === uuid);
    if (!record) return;

    const employee = employees.find(emp => emp.id === record.employee_id);
    const policy = policies.find(pol => pol.id === record.policy_id);

    document.getElementById('recordDetails').innerHTML = `
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Employee Information</h3>
                <p class="mt-1 text-sm text-gray-600">${employee ? employee.name : 'Unknown'}</p>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Overtime Details</h3>
                <div class="mt-2 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date</p>
                        <p class="mt-1 text-sm text-gray-900">${record.date}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Time</p>
                        <p class="mt-1 text-sm text-gray-900">${record.start_time} - ${record.end_time}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Hours</p>
                        <p class="mt-1 text-sm text-gray-900">${record.total_hours} hours</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Compensation</p>
                        <p class="mt-1 text-sm text-gray-900">$${record.compensation_amount.toFixed(2)}</p>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Reason</h3>
                <p class="mt-1 text-sm text-gray-600">${record.reason}</p>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Tasks Completed</h3>
                <p class="mt-1 text-sm text-gray-600">${record.tasks_completed}</p>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Status</h3>
                <div class="mt-2">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                        record.status === 'approved' ? 'bg-green-100 text-green-800' :
                        record.status === 'rejected' ? 'bg-red-100 text-red-800' :
                        'bg-yellow-100 text-yellow-800'
                    }">
                        ${record.status.charAt(0).toUpperCase() + record.status.slice(1)}
                    </span>
                </div>
                ${record.status === 'rejected' ? `
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-500">Rejection Reason</p>
                        <p class="mt-1 text-sm text-gray-600">${record.rejection_reason}</p>
                    </div>
                ` : ''}
            </div>
            ${record.status === 'pending' ? `
                <div class="flex justify-end space-x-4">
                    <button onclick="approveRecord('${record.uuid}')"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Approve
                    </button>
                    <button onclick="rejectRecord('${record.uuid}')"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Reject
                    </button>
                </div>
            ` : ''}
        </div>
    `;

    document.getElementById('viewRecordModal').classList.remove('hidden');
}

function closeViewRecordModal() {
    document.getElementById('viewRecordModal').classList.add('hidden');
}

function editRecord(uuid) {
    const record = records.find(r => r.uuid === uuid);
    if (!record) return;

    document.getElementById('modalTitle').textContent = 'Edit Record';
    document.getElementById('recordUuid').value = record.uuid;
    document.getElementById('employee_id').value = record.employee_id;
    document.getElementById('policy_id').value = record.policy_id;
    document.getElementById('date').value = record.date;
    document.getElementById('start_time').value = record.start_time;
    document.getElementById('end_time').value = record.end_time;
    document.getElementById('reason').value = record.reason;
    document.getElementById('tasks_completed').value = record.tasks_completed;

    document.getElementById('recordModal').classList.remove('hidden');
}

function saveRecord(event) {
    event.preventDefault();
    const form = event.target;
    const uuid = document.getElementById('recordUuid').value;
    const isEdit = uuid !== '';

    const formData = new FormData(form);
    const data = {
        employee_id: formData.get('employee_id'),
        policy_id: formData.get('policy_id'),
        date: formData.get('date'),
        start_time: formData.get('start_time'),
        end_time: formData.get('end_time'),
        reason: formData.get('reason'),
        tasks_completed: formData.get('tasks_completed'),
        status: 'pending'
    };

    const url = isEdit ? `/api/overtime-records/${uuid}` : '/api/overtime-records';
    const method = isEdit ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
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
            closeRecordModal();
            loadRecords();
            showToast(isEdit ? 'Record updated successfully' : 'Record created successfully', 'success');
        } else {
            showToast(data.message || 'Failed to save record', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving record:', error);
        showToast('Failed to save record', 'error');
    });
}

function deleteRecord(uuid) {
    if (!confirm('Are you sure you want to delete this record?')) return;

    fetch(`/api/overtime-records/${uuid}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            loadRecords();
            showToast('Record deleted successfully', 'success');
        } else {
            showToast(data.message || 'Failed to delete record', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting record:', error);
        showToast('Failed to delete record', 'error');
    });
}

function approveRecord(uuid) {
    if (!confirm('Are you sure you want to approve this overtime record?')) return;

    fetch(`/api/overtime-records/${uuid}/approve`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeViewRecordModal();
            loadRecords();
            showToast('Record approved successfully', 'success');
        } else {
            showToast(data.message || 'Failed to approve record', 'error');
        }
    })
    .catch(error => {
        console.error('Error approving record:', error);
        showToast('Failed to approve record', 'error');
    });
}

function rejectRecord(uuid) {
    const reason = prompt('Please enter the reason for rejection:');
    if (!reason) return;

    fetch(`/api/overtime-records/${uuid}/reject`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ rejection_reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeViewRecordModal();
            loadRecords();
            showToast('Record rejected successfully', 'success');
        } else {
            showToast(data.message || 'Failed to reject record', 'error');
        }
    })
    .catch(error => {
        console.error('Error rejecting record:', error);
        showToast('Failed to reject record', 'error');
    });
}

function applyFilters() {
    loadRecords();
}

// Load data when the page loads
document.addEventListener('DOMContentLoaded', () => {
    loadEmployees();
    loadPolicies();
    loadRecords();
});
</script>
@endsection
