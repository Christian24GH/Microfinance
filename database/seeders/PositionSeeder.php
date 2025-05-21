<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        Position::insert([
            ['name' => 'Manager', 'description' => 'Department Manager'],
            ['name' => 'Staff', 'description' => 'Regular Staff'],
            ['name' => 'Developer', 'description' => 'Software Developer'],
            ['name' => 'Analyst', 'description' => 'Business Analyst'],
        ]);
    }
}
