<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'claim_id' => Claim::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['pending', 'processed', 'failed']),
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'cash', 'check']),
            'reference_number' => $this->faker->optional()->numerify('PAY-####'),
            'payment_date' => $this->faker->dateThisMonth(),
            'notes' => $this->faker->optional()->sentence(),
            'created_by' => User::factory(),
            'processed_by' => null,
            'processed_at' => null
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'processed_by' => null,
                'processed_at' => null
            ];
        });
    }

    public function processed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'processed',
                'processed_by' => User::factory(),
                'processed_at' => now()
            ];
        });
    }

    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed',
                'processed_by' => User::factory(),
                'processed_at' => now()
            ];
        });
    }
}
