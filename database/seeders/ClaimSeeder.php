<?php

namespace Database\Seeders;

use App\Models\Claim;
use App\Models\ClaimItem;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClaimSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::all();
        $users = User::all();

        if ($employees->isEmpty() || $users->isEmpty()) {
            $this->command->error('Please run EmployeeSeeder and UserSeeder first!');
            return;
        }

        // Create pending claims
        Claim::factory()
            ->count(5)
            ->pending()
            ->create()
            ->each(function ($claim) {
                ClaimItem::factory()
                    ->count(rand(1, 5))
                    ->create(['claim_id' => $claim->id]);
            });

        // Create approved claims
        Claim::factory()
            ->count(3)
            ->approved()
            ->create()
            ->each(function ($claim) {
                ClaimItem::factory()
                    ->count(rand(1, 5))
                    ->create(['claim_id' => $claim->id]);
            });

        // Create rejected claims
        Claim::factory()
            ->count(2)
            ->rejected()
            ->create()
            ->each(function ($claim) {
                ClaimItem::factory()
                    ->count(rand(1, 5))
                    ->create(['claim_id' => $claim->id]);
            });

        // Create paid claims
        Claim::factory()
            ->count(4)
            ->paid()
            ->create()
            ->each(function ($claim) {
                ClaimItem::factory()
                    ->count(rand(1, 5))
                    ->create(['claim_id' => $claim->id]);
            });

        $this->command->info('Claims seeded successfully!');
    }
}
