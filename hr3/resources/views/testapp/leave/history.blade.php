<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
        .history-card {
            transition: all 0.3s ease;
        }
        .history-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Leave History</h1>
            <p class="text-gray-600 mt-2">View your leave request history</p>
        </div>

        <!-- Leave Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Leaves</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $leaveRequests->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Approved Leaves</h3>
                <p class="text-3xl font-bold text-green-600">{{ $leaveRequests->where('status', 'approved')->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Pending Leaves</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $leaveRequests->where('status', 'pending')->count() }}</p>
            </div>
        </div>

        <!-- Leave History Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Leave History</h2>
                <div class="flex gap-2">
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <input type="month" id="monthFilter" class="form-control form-control-sm">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Days</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                        <tr class="history-card">
                            <td>{{ $request->leaveType->name }}</td>
                            <td>{{ $request->start_date->format('M d, Y') }}</td>
                            <td>{{ $request->end_date->format('M d, Y') }}</td>
                            <td>{{ $request->total_days }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($request->reason, 50) }}</td>
                            <td>
                                <button onclick="viewDetails({{ $request->id }})" class="btn btn-info btn-sm">
                                    View
                                </button>
                                @if($request->status === 'pending')
                                <button onclick="cancelRequest({{ $request->id }})" class="btn btn-danger btn-sm">
                                    Cancel
                                </button>
                                @endif
                            </td>
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
        // Filter functionality
        document.getElementById('statusFilter').addEventListener('change', filterTable);
        document.getElementById('monthFilter').addEventListener('change', filterTable);

        function filterTable() {
            const status = document.getElementById('statusFilter').value;
            const month = document.getElementById('monthFilter').value;
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const rowStatus = row.querySelector('td:nth-child(5)').textContent.trim().toLowerCase();
                const rowDate = row.querySelector('td:nth-child(2)').textContent;
                const rowMonth = new Date(rowDate).toISOString().slice(0, 7);

                const statusMatch = !status || rowStatus === status.toLowerCase();
                const monthMatch = !month || rowMonth === month;

                row.style.display = statusMatch && monthMatch ? '' : 'none';
            });
        }

        function viewDetails(id) {
            fetch(`/api/leave-requests/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const request = data.leave_request;
                        const details = `
                            Leave Type: ${request.leave_type.name}
                            Period: ${request.start_date} - ${request.end_date}
                            Total Days: ${request.total_days}
                            Status: ${request.status}
                            Reason: ${request.reason}
                            ${request.rejection_reason ? `Rejection Reason: ${request.rejection_reason}` : ''}
                        `;
                        alert(details);
                    }
                });
        }

        function cancelRequest(id) {
            if (confirm('Are you sure you want to cancel this leave request?')) {
                fetch(`/api/leave-requests/${id}/cancel`, {
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
                        alert('Failed to cancel leave request');
                    }
                });
            }
        }
    </script>
</body>
</html>
