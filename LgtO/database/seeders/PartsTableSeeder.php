<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parts_used')->insert([
            [
                'maintenance_log_id' => 1,
                'part_id'            => 1,
                'quantity_used'      => 2,
                'unit'               => 'pc',
            ],
            [
                'maintenance_log_id' => 1,
                'part_id'            => 2,
                'quantity_used'      => 5,
                'unit'               => 'liters',
            ],
            [
                'maintenance_log_id' => 2,
                'part_id'            => 3,
                'quantity_used'      => 1,
                'unit'               => 'pc',
            ],
        ]);
    }
}
