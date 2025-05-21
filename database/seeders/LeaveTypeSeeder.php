<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'description' => 'Regular annual leave for employees',
                'default_days' => 20,
                'requires_approval' => true,
                'is_active' => true
            ],
            [
                'name' => 'Sick Leave',
                'description' => 'Leave for medical reasons',
                'default_days' => 14,
                'requires_approval' => true,
                'is_active' => true
            ],
            [
                'name' => 'Maternity Leave',
                'description' => 'Leave for childbirth and childcare',
                'default_days' => 90,
                'requires_approval' => true,
                'is_active' => true
            ],
            [
                'name' => 'Paternity Leave',
                'description' => 'Leave for new fathers',
                'default_days' => 14,
                'requires_approval' => true,
                'is_active' => true
            ],
            [
                'name' => 'Unpaid Leave',
                'description' => 'Leave without pay',
                'default_days' => 30,
                'requires_approval' => true,
                'is_active' => true
            ]
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}
