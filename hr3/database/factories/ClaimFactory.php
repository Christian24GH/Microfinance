<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'claim_type' => $this->faker->randomElement(['travel', 'medical', 'office', 'other']),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'paid']),
            'submitted_at' => $this->faker->dateTimeThisMonth(),
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => null,
            'rejected_by' => null,
            'rejection_reason' => null,
            'notes' => $this->faker->optional()->sentence()
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'approved_at' => null,
                'approved_by' => null,
                'rejected_at' => null,
                'rejected_by' => null,
                'rejection_reason' => null
            ];
        });
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => 1,
                'rejected_at' => null,
                'rejected_by' => null,
                'rejection_reason' => null
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'approved_at' => null,
                'approved_by' => null,
                'rejected_at' => now(),
                'rejected_by' => 1,
                'rejection_reason' => $this->faker->sentence()
            ];
        });
    }

    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'paid',
                'approved_at' => now()->subDays(2),
                'approved_by' => 1,
                'rejected_at' => null,
                'rejected_by' => null,
                'rejection_reason' => null
            ];
        });
    }
}
