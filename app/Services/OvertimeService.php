<?php

namespace App\Services;

use App\Models\OvertimePolicy;
use App\Models\Employee;
use Carbon\Carbon;

class OvertimeService
{
    public function calculateTotalHours($date, $startTime, $endTime)
    {
        $start = Carbon::parse("$date $startTime");
        $end = Carbon::parse("$date $endTime");
        return round($end->diffInSeconds($start) / 3600, 2);
    }

    public function calculateCompensation($employeeId, $policyId, $totalHours)
    {
        $employee = Employee::findOrFail($employeeId);
        $policy = OvertimePolicy::findOrFail($policyId);
        $hourlyRate = $employee->hourly_rate ?? 0;
        return $totalHours * $hourlyRate * $policy->rate_multiplier;
    }

    public function validatePolicyConstraints($policyId, $totalHours)
    {
        $policy = OvertimePolicy::findOrFail($policyId);
        if ($totalHours < $policy->minimum_hours) {
            return [false, "Overtime hours must be at least {$policy->minimum_hours} hours"];
        }
        if ($totalHours > $policy->maximum_hours) {
            return [false, "Overtime hours cannot exceed {$policy->maximum_hours} hours"];
        }
        return [true, null];
    }
}
