@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-content-wrapper mx-auto" style="max-width: 1200px;">
    <div class="row mb-4">
        <!-- Employees Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-danger h-100">
                <div class="card-header bg-danger text-white d-flex align-items-center">
                    <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                    <span>EMPLOYEES</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Regular</span>
                        <span>{{ $employees->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Trainee</span>
                        <span>{{ $employees->where('status', 'inactive')->count() }}</span>
                    </div>
                    <h6 class="fw-bold">Newest Employees</h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Start Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees->sortByDesc('created_at')->take(3) as $emp)
                                <tr>
                                    <td>{{ $emp->name }}</td>
                                    <td>{{ $emp->position->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($emp->created_at)->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Attendances Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-success h-100">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="bi bi-clock-history me-2" style="font-size: 1.5rem;"></i>
                    <span>ATTENDANCES</span>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Real-Time Attendance Status</h6>
                    <table class="table table-sm mb-0" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceStatus as $status)
                                <tr>
                                    <td>{{ $status['name'] }}</td>
                                    <td>{{ $status['date_in'] ?? '-' }}</td>
                                    <td>{{ $status['time_in'] ? \Carbon\Carbon::createFromFormat('H:i:s', $status['time_in'])->format('g:i A') : '-' }}</td>
                                    <td>{{ $status['time_out'] ? \Carbon\Carbon::createFromFormat('H:i:s', $status['time_out'])->format('g:i A') : '-' }}</td>
                                    <td>{{ $status['status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                    function formatTime12h(timeStr) {
                        if (!timeStr) return '-';
                        const [h, m, s] = timeStr.split(':');
                        let hour = parseInt(h);
                        const min = m;
                        const ampm = hour >= 12 ? 'PM' : 'AM';
                        hour = hour % 12;
                        hour = hour ? hour : 12;
                        return `${hour}:${min} ${ampm}`;
                    }
                    function fetchAttendance() {
                        fetch('/dashboard/attendance-status')
                            .then(res => res.json())
                            .then(data => {
                                let tbody = document.querySelector('#attendanceTable tbody');
                                tbody.innerHTML = '';
                                data.forEach(status => {
                                    tbody.innerHTML += `<tr>
                                        <td>${status.name}</td>
                                        <td>${status.date_in ?? '-'}</td>
                                        <td>${formatTime12h(status.time_in)}</td>
                                        <td>${formatTime12h(status.time_out)}</td>
                                        <td>${status.status}</td>
                                    </tr>`;
                                });
                            });
                    }
                    setInterval(fetchAttendance, 10000);
                    </script>
                </div>
            </div>
        </div>
        <!-- Leaves of Absence Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-warning h-100">
                <div class="card-header bg-warning text-dark d-flex align-items-center">
                    <i class="bi bi-house-door-fill me-2" style="font-size: 1.5rem;"></i>
                    <span>LEAVES OF ABSENCE</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Approved</span>
                        <span>{{ $recentLeaves->where('status', 'approved')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pending</span>
                        <span>{{ $recentLeaves->where('status', 'pending')->count() }}</span>
                    </div>
                    <h6 class="fw-bold">Recent Leaves of Absence</h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->employee->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Pending Leave Approvals Card -->
        @if(!$pendingLeaves->isEmpty())
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-info h-100">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <i class="bi bi-hourglass-split me-2" style="font-size: 1.5rem;"></i>
                    <span>PENDING LEAVE APPROVALS</span>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Recent Leave Requests Awaiting Approval</h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->employee->name ?? 'N/A' }}</td>
                                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('leave.leave_approval') }}" class="btn btn-sm btn-outline-info mt-2">View All Leave Approvals</a>
                </div>
            </div>
        </div>
        @endif
        <!-- Timesheets Pending Approval Card -->
        @if(!$timesheetsPendingApproval->isEmpty())
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-primary h-100">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-clipboard-check me-2" style="font-size: 1.5rem;"></i>
                    <span>PENDING TIMESHEET APPROVALS</span>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Recent Timesheets Awaiting Approval</h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Period</th>
                                <th>Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timesheetsPendingApproval as $ts)
                                <tr>
                                    <td>{{ $ts->employee->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ts->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($ts->end_date)->format('M d') }}</td>
                                    <td>{{ number_format($ts->total_hours, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($timesheetsPendingApproval->count() >= 5)
                        <small class="text-muted d-block mt-2">Showing up to 5 pending timesheets.</small>
                    @endif
                    {{-- Link to a dedicated approval page if it exists and user has permission --}}
                    @if(Route::has('timesheet.approval'))
                        <a href="{{ route('timesheet.approval') }}" class="btn btn-sm btn-outline-primary mt-2">View All Approvals</a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
