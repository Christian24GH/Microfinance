@extends('layouts.app')

@section('title', 'Approval Workflow Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-4 px-4">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Approval Workflow Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Configure and manage approval workflows for timesheets</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="showAddWorkflowModal()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition-all duration-200 shadow-sm hover:shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Workflow
                    </button>
                    <button onclick="refreshWorkflows()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition-all duration-200 shadow-sm hover:shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Workflows Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="workflowsGrid">
            <!-- Workflows will be loaded here -->
        </div>

        <!-- Add Workflow Modal -->
        <div id="addWorkflowModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Add New Workflow</h2>
                            <p class="text-sm text-gray-600 mt-1">Configure a new approval workflow</p>
                        </div>
                        <button onclick="closeModal('addWorkflowModal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <form id="addWorkflowForm" class="p-6" onsubmit="saveWorkflow(event)">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Workflow Name</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select id="type" name="type" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="timesheet">Timesheet</option>
                                <option value="leave">Leave</option>
                                <option value="claim">Claim</option>
                            </select>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('addWorkflowModal')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Save Workflow
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Stage Modal -->
        <div id="addStageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Add Approval Stage</h2>
                            <p class="text-sm text-gray-600 mt-1">Configure a new approval stage</p>
                        </div>
                        <button onclick="closeModal('addStageModal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <form id="addStageForm" class="p-6" onsubmit="saveStage(event)">
                    <input type="hidden" id="workflow_id" name="workflow_id">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="stage_name" class="block text-sm font-medium text-gray-700 mb-2">Stage Name</label>
                            <input type="text" id="stage_name" name="name" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="stage_order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input type="number" id="stage_order" name="stage_order" required min="1"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="approver_type" class="block text-sm font-medium text-gray-700 mb-2">Approver Type</label>
                            <select id="approver_type" name="approver_type" required onchange="loadApprovers(this.value)"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Select Type</option>
                                <option value="role">Role</option>
                                <option value="employee">Employee</option>
                                <option value="department">Department</option>
                            </select>
                        </div>
                        <div>
                            <label for="approver_id" class="block text-sm font-medium text-gray-700 mb-2">Approver</label>
                            <select id="approver_id" name="approver_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Select Approver</option>
                            </select>
                        </div>
                        <div>
                            <label for="is_final" class="block text-sm font-medium text-gray-700 mb-2">Is Final Stage?</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="is_final" name="is_final" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">This is the final approval stage</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="stage_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="stage_description" name="description"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('addStageModal')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Save Stage
                        </button>
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
<script>
function showAddWorkflowModal() {
    document.getElementById('addWorkflowModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('name').focus();
    }, 100);
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function refreshWorkflows() {
    fetch('/api/workflows')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const grid = document.getElementById('workflowsGrid');
                grid.innerHTML = '';
                data.workflows.forEach(workflow => {
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200';
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">${workflow.name}</h3>
                                <p class="text-sm text-gray-600">${workflow.type}</p>
                                <p class="text-sm text-gray-500">${workflow.description || 'No description'}</p>
                            </div>
                            <span class="px-3 py-1 text-sm font-medium ${workflow.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} rounded-full">
                                ${workflow.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Approval Stages</h4>
                            <div class="space-y-2">
                                ${workflow.stages.map(stage => `
                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium">${stage.name}</p>
                                            <p class="text-xs text-gray-500">${stage.approver_type} - ${stage.approver_name}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="editStage(${stage.id})" class="text-blue-600 hover:text-blue-800">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteStage(${stage.id})" class="text-red-600 hover:text-red-800">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="showAddStageModal(${workflow.id})"
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Stage
                            </button>
                            <button onclick="editWorkflow(${workflow.id})"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </button>
                            <button onclick="deleteWorkflow(${workflow.id})"
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                        </div>
                    `;
                    grid.appendChild(card);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load workflows', 'error');
        });
}

function saveWorkflow(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    fetch('/api/workflows', {
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
            closeModal('addWorkflowModal');
            form.reset();
            showToast('Workflow added successfully', 'success');
            refreshWorkflows();
        } else {
            showToast(data.message || 'Failed to add workflow', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to add workflow', 'error');
    });
}

function showAddStageModal(workflowId) {
    document.getElementById('workflow_id').value = workflowId;
    document.getElementById('addStageModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('stage_name').focus();
    }, 100);
}

function loadApprovers(type) {
    const approverSelect = document.getElementById('approver_id');
    approverSelect.innerHTML = '<option value="">Loading...</option>';

    fetch(`/api/workflows/approvers/${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                approverSelect.innerHTML = '<option value="">Select Approver</option>';
                data.approvers.forEach(approver => {
                    const option = document.createElement('option');
                    option.value = approver.id;
                    option.textContent = approver.name;
                    approverSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            approverSelect.innerHTML = '<option value="">Failed to load approvers</option>';
        });
}

function saveStage(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    data.is_final = document.getElementById('is_final').checked;

    fetch('/api/workflows/stages', {
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
            closeModal('addStageModal');
            form.reset();
            showToast('Stage added successfully', 'success');
            refreshWorkflows();
        } else {
            showToast(data.message || 'Failed to add stage', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to add stage', 'error');
    });
}

function editWorkflow(id) {
    fetch(`/api/workflows/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const workflow = data.workflow;
                const form = document.getElementById('addWorkflowForm');

                form.name.value = workflow.name;
                form.type.value = workflow.type;
                form.description.value = workflow.description || '';

                form.onsubmit = function(e) {
                    e.preventDefault();
                    updateWorkflow(id, new FormData(form));
                };

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.textContent = 'Update Workflow';

                showAddWorkflowModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load workflow details', 'error');
        });
}

function updateWorkflow(id, formData) {
    const data = Object.fromEntries(formData.entries());

    fetch(`/api/workflows/${id}`, {
        method: 'PUT',
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
            closeModal('addWorkflowModal');
            showToast('Workflow updated successfully', 'success');
            refreshWorkflows();
        } else {
            showToast(data.message || 'Failed to update workflow', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to update workflow', 'error');
    });
}

function deleteWorkflow(id) {
    if (!confirm('Are you sure you want to delete this workflow?')) return;

    fetch(`/api/workflows/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Workflow deleted successfully', 'success');
            refreshWorkflows();
        } else {
            showToast(data.message || 'Failed to delete workflow', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to delete workflow', 'error');
    });
}

function editStage(id) {
    fetch(`/api/workflows/stages/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const stage = data.stage;
                const form = document.getElementById('addStageForm');

                form.workflow_id.value = stage.workflow_id;
                form.stage_name.value = stage.name;
                form.stage_order.value = stage.stage_order;
                form.approver_type.value = stage.approver_type;
                form.is_final.checked = stage.is_final;
                form.stage_description.value = stage.description || '';

                // Load approvers for the selected type
                loadApprovers(stage.approver_type).then(() => {
                    form.approver_id.value = stage.approver_id;
                });

                form.onsubmit = function(e) {
                    e.preventDefault();
                    updateStage(id, new FormData(form));
                };

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.textContent = 'Update Stage';

                showAddStageModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load stage details', 'error');
        });
}

function updateStage(id, formData) {
    const data = Object.fromEntries(formData.entries());
    data.is_final = document.getElementById('is_final').checked;

    fetch(`/api/workflows/stages/${id}`, {
        method: 'PUT',
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
            closeModal('addStageModal');
            showToast('Stage updated successfully', 'success');
            refreshWorkflows();
        } else {
            showToast(data.message || 'Failed to update stage', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to update stage', 'error');
    });
}

function deleteStage(id) {
    if (!confirm('Are you sure you want to delete this stage?')) return;

    fetch(`/api/workflows/stages/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Stage deleted successfully', 'success');
            refreshWorkflows();
        } else {
            showToast(data.message || 'Failed to delete stage', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to delete stage', 'error');
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    refreshWorkflows();
});
</script>
@endsection
