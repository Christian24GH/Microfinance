<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $roles = [
            'Client',
            'HR Supervisor',
            'Finance Officer',
            'Audit Officer',
            'Maintenance Staff',
            'Technician',
            'Maintenance Admin',
            'Project Manager',
            'Asset Admin',
            'Asset Staff',
            'Asset Analyst',
            'Warehouse Manager',
            'Inventory Staff',
            'Supplier',
            'Procurement Administrator',
            'Quality Inspector',
            'Procurement Analyst',
            'Communication Officer',
            'Payroll Officer',
            'Super Admin',
            'Employee',
            'HR Administrator',
            'Manager/Supervisor',
            'Team Member',
            'Team Leader',
            'Loan Officer'
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'role' => $role
            ]);
        }
    }
}
