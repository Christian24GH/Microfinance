<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartsInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parts_inventory')->insert([
            [
                'part_name'         => 'LED Light Bulb',
                'part_number'       => 'ELEC-001',
                'description'       => 'Energy-saving LED bulb, 9W',
                'category'          => 'electrical',
                'quantity_in_stock' => 150,
                'unit'              => 'pc',
                'reorder_level'     => 50,
                'unit_cost'         => 120.00,
                'status'            => 'active',
            ],
            [
                'part_name'         => 'Motor Oil',
                'part_number'       => 'MECH-002',
                'description'       => 'Premium synthetic motor oil, 4L',
                'category'          => 'mechanical',
                'quantity_in_stock' => 40,
                'unit'              => 'liters',
                'reorder_level'     => 10,
                'unit_cost'         => 950.00,
                'status'            => 'active',
            ],
            [
                'part_name'         => 'Fuse 10A',
                'part_number'       => 'ELEC-003',
                'description'       => '10A electrical fuse',
                'category'          => 'electrical',
                'quantity_in_stock' => 80,
                'unit'              => 'pc',
                'reorder_level'     => 20,
                'unit_cost'         => 25.50,
                'status'            => 'discontinued',
            ],
        ]);
    }
}
