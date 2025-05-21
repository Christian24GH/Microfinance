<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClaimType;

class ClaimTypeSeeder extends Seeder
{
    public function run()
    {
        ClaimType::insert([
            [
                'name' => 'Medical',
                'description' => 'Medical reimbursement claims',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel and transportation claims',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meal',
                'description' => 'Meal and food allowance claims',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Other',
                'description' => 'Other types of claims',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
