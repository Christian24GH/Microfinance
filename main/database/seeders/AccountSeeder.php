<?php

namespace Database\Seeders;

use App\Models\Accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
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

        for ($i = 1; $i <= 10; $i++) {
            Accounts::create([
                'fullname' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'role' => $roles[array_rand($roles)],
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
