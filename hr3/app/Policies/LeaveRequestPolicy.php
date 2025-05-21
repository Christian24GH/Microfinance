<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

    public function view(Employee $employee, LeaveRequest $leaveRequest)
    {
        return $employee->id === $leaveRequest->employee_id ||
               $employee->isManager() ||
               $employee->isHR();
    }

    public function create(Employee $employee)
    {
        return true; // All authenticated employees can create leave requests
    }

    public function cancel(Employee $employee, LeaveRequest $leaveRequest)
    {
        return $employee->id === $leaveRequest->employee_id &&
               $leaveRequest->status === 'pending';
    }

    public function approve(Employee $employee, LeaveRequest $leaveRequest)
    {
        return ($employee->isManager() || $employee->isHR()) &&
               $leaveRequest->status === 'pending';
    }

    public function reject(Employee $employee, LeaveRequest $leaveRequest)
    {
        return ($employee->isManager() || $employee->isHR()) &&
               $leaveRequest->status === 'pending';
    }
}
