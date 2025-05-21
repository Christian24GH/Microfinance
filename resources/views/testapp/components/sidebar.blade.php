<div class="d-flex flex-column justify-content-between h-100 bg-white border-end" style="width: 260px; min-height: 100vh;">
    <!-- Logo and App Name -->
    <div>
        <div class="d-flex align-items-center gap-2 px-4 py-4 border-bottom">
            <img src="/logo.png" alt="TruLend Logo" style="height: 40px; width: 40px; object-fit:contain;">
            <span class="fw-bold fs-4" style="letter-spacing:1px;">TruLend</span>
        </div>
        <nav class="nav flex-column mt-3 px-3">
            <span class="text-muted text-uppercase small mb-2 mt-2">General</span>
            <a class="nav-link py-2 px-2 fw-semibold {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a class="nav-link py-2 px-2 {{ request()->routeIs('timeattendance.clocking') ? 'active' : '' }}" href="{{ route('timeattendance.clocking') }}">
                <i class="bi bi-calendar-event me-2"></i> Time & Attendance
            </a>
            <a class="nav-link py-2 px-2 {{ request()->routeIs('employee.index') ? 'active' : '' }}" href="{{ route('employee.index') }}">
                <i class="bi bi-stack me-2"></i> Timesheet
            </a>
            <a class="nav-link py-2 px-2 {{ request()->routeIs('claims.claim_submission') ? 'active' : '' }}" href="{{ route('claims.claim_submission') }}">
                <i class="bi bi-backpack me-2"></i> Claims & Reimbursements
            </a>
            <a class="nav-link py-2 px-2 {{ request()->routeIs('leave.leave_request') ? 'active' : '' }}" href="{{ route('leave.leave_request') }}">
                <i class="bi bi-house-door-fill me-2"></i> Leave Management
            </a>
            <a class="nav-link py-2 px-2 {{ request()->routeIs('shift.index') ? 'active' : '' }}" href="{{ route('shift.index') }}">
                <i class="bi bi-calendar-week me-2"></i> Shift & Schedule
            </a>
        </nav>
    </div>
    <!-- User Info and Logout -->
    <div class="px-3 pb-4">
        <div class="bg-light rounded-3 p-3 mb-3">
            <div class="fw-semibold">{{ Auth::user()->name ?? 'Username' }}</div>
            <div class="text-muted small">{{ Auth::user()->role ?? 'Role' }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill">Logout</button>
        </form>
    </div>
</div>
