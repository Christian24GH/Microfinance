<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Approval</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
        .approval-card {
            transition: all 0.3s ease;
        }
        .approval-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Leave Approval</h1>
            <p class="text-gray-600 mt-2">Review and manage leave requests</p>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Pending Approvals</h2>
            <div class="overflow-x-auto">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests->where('status', 'pending') as $request)
                        <tr class="approval-card">
                            <td>{{ $request->employee->name }}</td>
                            <td>{{ $request->leaveType->name }}</td>
                            <td>{{ $request->start_date->format('M d, Y') }}</td>
                            <td>{{ $request->end_date->format('M d, Y') }}</td>
                            <td>{{ Str::limit($request->reason, 50) }}</td>
                            <td>
                                <div class="btn-group">
                                    <button onclick="approveLeave({{ $request->id }})" class="btn btn-success btn-sm">
                                        Approve
                                    </button>
                                    <button onclick="rejectLeave({{ $request->id }})" class="btn btn-danger btn-sm">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Approval History -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Approval History</h2>
            <div class="overflow-x-auto">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Approved/Rejected By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests->where('status', '!=', 'pending') as $request)
                        <tr class="approval-card">
                            <td>{{ $request->employee->name }}</td>
                            <td>{{ $request->leaveType->name }}</td>
                            <td>{{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status === 'approved' ? 'success' : 'danger' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->approver ? $request->approver->name : '-' }}</td>
                            <td>{{ $request->approved_at ? $request->approved_at->format('M d, Y H:i') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        function approveLeave(id) {
            if (confirm('Are you sure you want to approve this leave request?')) {
                fetch(`/api/leave-requests/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to approve leave request');
                    }
                });
            }
        }

        function rejectLeave(id) {
            const reason = prompt('Please enter rejection reason:');
            if (reason) {
                fetch(`/api/leave-requests/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ rejection_reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to reject leave request');
                    }
                });
            }
        }
    </script>
</body>
</html>
