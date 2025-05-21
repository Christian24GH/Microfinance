<?php

namespace Database\Seeders;

use App\Models\Claim;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $approvedClaims = Claim::where('status', 'approved')->get();
        $users = User::all();

        if ($approvedClaims->isEmpty() || $users->isEmpty()) {
            $this->command->error('Please run ClaimSeeder and UserSeeder first!');
            return;
        }

        // Create pending payments for approved claims
        foreach ($approvedClaims as $claim) {
            Payment::factory()
                ->pending()
                ->create([
                    'claim_id' => $claim->id,
                    'amount' => $claim->total_amount,
                    'created_by' => $users->random()->id
                ]);
        }

        // Create some processed payments
        Payment::factory()
            ->count(3)
            ->processed()
            ->create()
            ->each(function ($payment) use ($users) {
                $payment->claim->update(['status' => 'paid']);
            });

        // Create some failed payments
        Payment::factory()
            ->count(2)
            ->failed()
            ->create();

        $this->command->info('Payments seeded successfully!');
    }
}
