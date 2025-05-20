<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class AssetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['vehicle', 'electronic', 'furniture', 'building', 'others'];
        $statuses = ['active', 'under repair', 'decommissioned'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('assets')->insert([
                'asset_tag' => strtoupper(Str::random(8)),
                'category' => $categories[array_rand($categories)],
                'status' => $statuses[array_rand($statuses)],
                'purchase_date' => Carbon::now()->subDays(rand(1, 1000))->toDateString(),
            ]);
        }
    }
}
