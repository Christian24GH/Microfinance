@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Overtime Policies</h1>
        <button onclick="openAddPolicyModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            Add New Policy
        </button>
    </div>

    <!-- Policies List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate Multiplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Frequency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="policiesList">
                    <!-- Policies will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Policy Modal -->
    <div id="policyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 id="modalTitle" class="text-xl font-semibold text-gray-900">Add New Policy</h2>
                    <button onclick="closePolicyModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <form id="policyForm" class="p-6" onsubmit="savePolicy(event)">
                <input type="hidden" id="policyUuid">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Policy Name</label>
                        <input type="text" id="name" name="name" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="rate_multiplier" class="block text-sm font-medium text-gray-700 mb-2">Rate Multiplier</label>
                        <input type="number" id="rate_multiplier" name="rate_multiplier" step="0.01" min="1" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="minimum_hours" class="block text-sm font-medium text-gray-700 mb-2">Minimum Hours</label>
                        <input type="number" id="minimum_hours" name="minimum_hours" step="0.5" min="0" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="maximum_hours" class="block text-sm font-medium text-gray-700 mb-2">Maximum Hours</label>
                        <input type="number" id="maximum_hours" name="maximum_hours" step="0.5" min="0" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="payment_frequency" class="block text-sm font-medium text-gray-700 mb-2">Payment Frequency</label>
                        <select id="payment_frequency" name="payment_frequency" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="weekly">Weekly</option>
                            <option value="biweekly">Bi-weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label for="approval_required" class="block text-sm font-medium text-gray-700 mb-2">Approval Required</label>
                        <select id="approval_required" name="approval_required" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="eligibility_criteria" class="block text-sm font-medium text-gray-700 mb-2">Eligibility Criteria</label>
                        <textarea id="eligibility_criteria" name="eligibility_criteria" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter each criterion on a new line</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="closePolicyModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Save Policy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let policies = [];

function loadPolicies() {
    fetch('/api/overtime-policies')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                policies = data.policies;
                renderPolicies();
            }
        })
        .catch(error => {
            console.error('Error loading policies:', error);
            showToast('Failed to load policies', 'error');
        });
}

function renderPolicies() {
    const tbody = document.getElementById('policiesList');
    tbody.innerHTML = policies.map(policy => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${policy.name}</div>
                <div class="text-sm text-gray-500">${policy.description || ''}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${policy.rate_multiplier}x</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${policy.minimum_hours} - ${policy.maximum_hours} hours</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 capitalize">${policy.payment_frequency}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${policy.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${policy.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editPolicy('${policy.uuid}')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                <button onclick="togglePolicyStatus('${policy.uuid}')" class="text-yellow-600 hover:text-yellow-900 mr-3">
                    ${policy.is_active ? 'Deactivate' : 'Activate'}
                </button>
                <button onclick="deletePolicy('${policy.uuid}')" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
        </tr>
    `).join('');
}

function openAddPolicyModal() {
    document.getElementById('modalTitle').textContent = 'Add New Policy';
    document.getElementById('policyForm').reset();
    document.getElementById('policyUuid').value = '';
    document.getElementById('policyModal').classList.remove('hidden');
}

function closePolicyModal() {
    document.getElementById('policyModal').classList.add('hidden');
}

function editPolicy(uuid) {
    const policy = policies.find(p => p.uuid === uuid);
    if (!policy) return;

    document.getElementById('modalTitle').textContent = 'Edit Policy';
    document.getElementById('policyUuid').value = policy.uuid;
    document.getElementById('name').value = policy.name;
    document.getElementById('description').value = policy.description || '';
    document.getElementById('rate_multiplier').value = policy.rate_multiplier;
    document.getElementById('minimum_hours').value = policy.minimum_hours;
    document.getElementById('maximum_hours').value = policy.maximum_hours;
    document.getElementById('payment_frequency').value = policy.payment_frequency;
    document.getElementById('approval_required').value = policy.approval_required ? '1' : '0';
    document.getElementById('eligibility_criteria').value = Array.isArray(policy.eligibility_criteria)
        ? policy.eligibility_criteria.join('\n')
        : '';

    document.getElementById('policyModal').classList.remove('hidden');
}

function savePolicy(event) {
    event.preventDefault();
    const form = event.target;
    const uuid = document.getElementById('policyUuid').value;
    const isEdit = uuid !== '';

    const formData = new FormData(form);
    const data = {
        name: formData.get('name'),
        description: formData.get('description'),
        rate_multiplier: parseFloat(formData.get('rate_multiplier')),
        minimum_hours: parseFloat(formData.get('minimum_hours')),
        maximum_hours: parseFloat(formData.get('maximum_hours')),
        payment_frequency: formData.get('payment_frequency'),
        approval_required: formData.get('approval_required') === '1',
        eligibility_criteria: formData.get('eligibility_criteria')
            .split('\n')
            .filter(c => c.trim() !== '')
    };

    const url = isEdit ? `/api/overtime-policies/${uuid}` : '/api/overtime-policies';
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
            closePolicyModal();
            loadPolicies();
            showToast(isEdit ? 'Policy updated successfully' : 'Policy created successfully', 'success');
        } else {
            showToast(data.message || 'Failed to save policy', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving policy:', error);
        showToast('Failed to save policy', 'error');
    });
}

function togglePolicyStatus(uuid) {
    if (!confirm('Are you sure you want to change this policy\'s status?')) return;

    fetch(`/api/overtime-policies/${uuid}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            loadPolicies();
            showToast('Policy status updated successfully', 'success');
        } else {
            showToast(data.message || 'Failed to update policy status', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating policy status:', error);
        showToast('Failed to update policy status', 'error');
    });
}

function deletePolicy(uuid) {
    if (!confirm('Are you sure you want to delete this policy?')) return;

    fetch(`/api/overtime-policies/${uuid}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            loadPolicies();
            showToast('Policy deleted successfully', 'success');
        } else {
            showToast(data.message || 'Failed to delete policy', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting policy:', error);
        showToast('Failed to delete policy', 'error');
    });
}

// Load policies when the page loads
document.addEventListener('DOMContentLoaded', loadPolicies);
</script>
@endsection
