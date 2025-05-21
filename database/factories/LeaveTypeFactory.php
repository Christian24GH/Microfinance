<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(),
            'default_days' => $this->faker->numberBetween(5, 30),
            'requires_approval' => $this->faker->boolean(80),
            'is_active' => true
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false
            ];
        });
    }

    public function noApproval()
    {
        return $this->state(function (array $attributes) {
            return [
                'requires_approval' => false
            ];
        });
    }
}
