<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::insert([
            ['name' => 'HR', 'description' => 'Human Resources'],
            ['name' => 'IT', 'description' => 'Information Technology'],
            ['name' => 'Finance', 'description' => 'Finance Department'],
            ['name' => 'Operations', 'description' => 'Operations Department'],
        ]);
    }
}
