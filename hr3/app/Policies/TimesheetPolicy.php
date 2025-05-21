<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Timesheet;

class TimesheetPolicy
{
    public function approve(User $user, Timesheet $timesheet)
    {
        return true; // Allow all users for testing
    }
}
