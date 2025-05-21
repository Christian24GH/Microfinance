<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\TimeEntry;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     DepartmentSeeder::class,
        //     PositionSeeder::class,
        // ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed default departments
        $departments = ['IT', 'HR', 'Finance', 'Admin', 'Operations'];
        foreach ($departments as $dept) {
            \App\Models\Department::firstOrCreate(['name' => $dept], ['status' => 'active']);
        }

        // Seed default positions
        $positions = ['Software Engineer', 'HR Manager', 'Finance Manager', 'Admin Officer', 'Operations Manager'];
        foreach ($positions as $pos) {
            \App\Models\Position::firstOrCreate(['name' => $pos], ['status' => 'active']);
        }

        // Create sample employees
        $employees = [
            [
                'employee_id' => 'EMP001',
                'name' => 'Jericho Rosales',
                'department' => 'IT',
                'position' => 'Software Engineer',
                'email' => 'jericho@example.com',
                'phone' => '09123456789',
                'hire_date' => '2023-01-15',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP002',
                'name' => 'Bea Alonzo',
                'department' => 'HR',
                'position' => 'HR Manager',
                'email' => 'bea@example.com',
                'phone' => '09234567890',
                'hire_date' => '2023-02-20',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP003',
                'name' => 'John Lloyd Cruz',
                'department' => 'Finance',
                'position' => 'Finance Manager',
                'email' => 'john@example.com',
                'phone' => '09345678901',
                'hire_date' => '2023-03-10',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP004',
                'name' => 'Angel Locsin',
                'department' => 'Admin',
                'position' => 'Admin Officer',
                'email' => 'angel@example.com',
                'phone' => '09456789012',
                'hire_date' => '2023-04-05',
                'status' => 'active'
            ],
            [
                'employee_id' => 'EMP005',
                'name' => 'Coco Martin',
                'department' => 'Operations',
                'position' => 'Operations Manager',
                'email' => 'coco@example.com',
                'phone' => '09567890123',
                'hire_date' => '2023-05-12',
                'status' => 'active'
            ]
        ];

        foreach ($employees as $employeeData) {
            $department = \App\Models\Department::whereRaw('LOWER(name) = ?', [strtolower($employeeData['department'])])->first();
            $position = \App\Models\Position::whereRaw('LOWER(name) = ?', [strtolower($employeeData['position'])])->first();
            $employee = Employee::create([
                'employee_id' => $employeeData['employee_id'],
                'name' => $employeeData['name'],
                'department_id' => $department ? $department->id : null,
                'position_id' => $position ? $position->id : null,
                'email' => $employeeData['email'],
                'phone' => $employeeData['phone'],
                'hire_date' => $employeeData['hire_date'],
                'status' => $employeeData['status'],
            ]);

            // Create a demo timesheet for the last 5 days
            $startDate = Carbon::now()->subDays(4)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
            $timesheet = \App\Models\Timesheet::create([
                'employee_id' => $employee->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_hours' => 0,
                'status' => 'pending',
            ]);

            // Create sample time entries for each day, linked to the timesheet
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subDays($i);
                $timeIn = Carbon::createFromTime(8, 0, 0);
                $timeOut = Carbon::createFromTime(17, 0, 0);

                $entry = TimeEntry::create([
                    'employee_id' => $employee->id,
                    'timesheet_id' => $timesheet->id,
                    'date' => $date,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'total_hours' => 9.00,
                    'status' => 'pending',
                ]);
            }
            // Update total hours in timesheet
            $timesheet->total_hours = $timesheet->entries()->sum('total_hours');
            $timesheet->save();
        }

        // Seed a default timesheet approval workflow
        $workflow = \App\Models\Workflow::create([
            'name' => 'Default Timesheet Approval',
            'type' => 'timesheet',
            'description' => 'Standard two-stage approval for timesheets',
            'is_active' => true
        ]);

        $stages = [
            [
                'workflow_id' => $workflow->id,
                'name' => 'Supervisor Approval',
                'stage_order' => 1,
                'approver_type' => 'role',
                'approver_id' => 2, // Example: Supervisor role ID
                'is_final' => false,
                'description' => 'Supervisor reviews and approves timesheet.'
            ],
            [
                'workflow_id' => $workflow->id,
                'name' => 'HR Approval',
                'stage_order' => 2,
                'approver_type' => 'role',
                'approver_id' => 3, // Example: HR role ID
                'is_final' => true,
                'description' => 'HR gives final approval.'
            ]
        ];
        foreach ($stages as $stage) {
            \App\Models\WorkflowStage::create($stage);
        }

        $this->call([
            LeaveTypeSeeder::class,
            ClaimTypeSeeder::class,
        ]);
    }
}
