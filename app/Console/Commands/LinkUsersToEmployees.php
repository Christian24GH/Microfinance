<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Employee;

class LinkUsersToEmployees extends Command
{
    protected $signature = 'employees:link-users';
    protected $description = 'Link users to employees by matching email addresses';

    public function handle()
    {
        $users = User::all();
        $linked = 0;
        foreach ($users as $user) {
            $employee = Employee::where('email', $user->email)->first();
            if ($employee && !$employee->user_id) {
                $employee->user_id = $user->id;
                $employee->save();
                $linked++;
            }
        }
        $this->info("Linked {$linked} employees to users.");
    }
}
