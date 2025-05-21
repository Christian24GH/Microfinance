@extends('layouts.app')

@section('title', 'Shift Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Shifts</h6>
                            <h2 class="mb-0 mt-2" id="totalShifts">{{ $shifts->count() }}</h2>
                        </div>
                        <i class="bi bi-calendar-week fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">Active shift patterns in the system</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Active Employees</h6>
                            <h2 class="mb-0 mt-2" id="activeEmployees">{{ $activeEmployees }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">Currently active workforce</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Today's Schedule</h6>
                            <h2 class="mb-0 mt-2" id="todaySchedules">{{ $todaySchedules }}</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">Scheduled shifts for today</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pending Assignments</h6>
                            <h2 class="mb-0 mt-2" id="pendingAssignments">{{ $pendingAssignments }}</h2>
                        </div>
                        <i class="bi bi-clock-history fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">Awaiting approval</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Shift Creation Form -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Shift</h5>
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div class="card-body">
                    <form id="shiftForm" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Shift Name</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="e.g., Morning Shift">
                            <div class="form-text">Enter a descriptive name for the shift</div>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                            <div class="form-text">When does this shift begin?</div>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                            <div class="form-text">When does this shift end?</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Create Shift
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Shifts List -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage Shifts</h5>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search shifts..." id="shiftSearch">
                        <button class="btn btn-light" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Shift Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="shiftsTableBody">
                                @foreach($shifts as $shift)
                                <tr data-id="{{ $shift->id }}">
                                    <td>{{ $shift->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($shift->start_time)->diffInHours(\Carbon\Carbon::parse($shift->end_time)) }} hours</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info edit-shift" data-id="{{ $shift->id }}"
                                                data-bs-toggle="tooltip" title="Edit Shift">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-shift" data-id="{{ $shift->id }}"
                                                data-bs-toggle="tooltip" title="Delete Shift">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Shift Modal -->
<div class="modal fade" id="editShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Shift</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editShiftForm">
                    @csrf
                    <input type="hidden" id="edit_shift_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Shift Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="edit_start_time" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="edit_end_time" name="end_time" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEditShift">
                    <i class="bi bi-save me-2"></i>Save changes
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.btn-group .btn {
    margin: 0 2px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Handle shift creation
    const shiftForm = document.getElementById('shiftForm');
    shiftForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('/shifts', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                const data = await response.json();
                // Add new shift to table
                const tbody = document.getElementById('shiftsTableBody');
                const newRow = createShiftRow(data.shift);
                tbody.insertBefore(newRow, tbody.firstChild);

                // Reset form
                this.reset();
                this.classList.remove('was-validated');

                // Show success message
                showAlert('success', 'Shift created successfully!');

                // Update stats
                updateStats();
            }
        } catch (error) {
            showAlert('danger', 'Error creating shift. Please try again.');
        }
    });

    // Handle shift editing
    const editModal = new bootstrap.Modal(document.getElementById('editShiftModal'));
    document.querySelectorAll('.edit-shift').forEach(button => {
        button.addEventListener('click', async function() {
            const shiftId = this.dataset.id;
            try {
                const response = await fetch(`/shifts/${shiftId}/edit`);
                const data = await response.json();

                document.getElementById('edit_shift_id').value = data.id;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_start_time').value = data.start_time;
                document.getElementById('edit_end_time').value = data.end_time;

                editModal.show();
            } catch (error) {
                showAlert('danger', 'Error loading shift data.');
            }
        });
    });

    // Handle shift update
    document.getElementById('saveEditShift').addEventListener('click', async function() {
        const form = document.getElementById('editShiftForm');
        const formData = new FormData(form);
        const shiftId = formData.get('id');

        try {
            const response = await fetch(`/shifts/${shiftId}`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                const data = await response.json();
                // Update shift in table
                const row = document.querySelector(`tr[data-id="${shiftId}"]`);
                const newRow = createShiftRow(data.shift);
                row.replaceWith(newRow);

                editModal.hide();
                showAlert('success', 'Shift updated successfully!');

                // Update stats
                updateStats();
            }
        } catch (error) {
            showAlert('danger', 'Error updating shift. Please try again.');
        }
    });

    // Handle shift deletion
    document.querySelectorAll('.delete-shift').forEach(button => {
        button.addEventListener('click', async function() {
            if (confirm('Are you sure you want to delete this shift?')) {
                const shiftId = this.dataset.id;
                try {
                    const response = await fetch(`/shifts/${shiftId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        // Remove shift from table
                        this.closest('tr').remove();
                        showAlert('success', 'Shift deleted successfully!');

                        // Update stats
                        updateStats();
                    }
                } catch (error) {
                    showAlert('danger', 'Error deleting shift. Please try again.');
                }
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('shiftSearch');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#shiftsTableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Real-time updates using Laravel Echo
    window.Echo.private('shifts')
        .listen('ShiftUpdated', (e) => {
            updateStats();
            // Refresh the table if needed
            location.reload();
        });
});

// Helper function to create shift row
function createShiftRow(shift) {
    const tr = document.createElement('tr');
    tr.dataset.id = shift.id;
    tr.innerHTML = `
        <td>${shift.name}</td>
        <td>${new Date(shift.start_time).toLocaleTimeString()}</td>
        <td>${new Date(shift.end_time).toLocaleTimeString()}</td>
        <td>${calculateDuration(shift.start_time, shift.end_time)} hours</td>
        <td>
            <div class="btn-group">
                <button class="btn btn-sm btn-info edit-shift" data-id="${shift.id}"
                    data-bs-toggle="tooltip" title="Edit Shift">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-shift" data-id="${shift.id}"
                    data-bs-toggle="tooltip" title="Delete Shift">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </td>
    `;
    return tr;
}

// Helper function to calculate duration
function calculateDuration(start, end) {
    const startTime = new Date(start);
    const endTime = new Date(end);
    return Math.abs(endTime - startTime) / 36e5; // Convert to hours
}

// Helper function to show alerts
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}

// Helper function to update stats
async function updateStats() {
    try {
        const response = await fetch('/shifts/stats');
        const stats = await response.json();

        document.getElementById('totalShifts').textContent = stats.total_shifts;
        document.getElementById('activeEmployees').textContent = stats.active_employees;
        document.getElementById('todaySchedules').textContent = stats.today_schedules;
        document.getElementById('pendingAssignments').textContent = stats.pending_assignments;
    } catch (error) {
        console.error('Error updating stats:', error);
    }
}
</script>
@endpush
