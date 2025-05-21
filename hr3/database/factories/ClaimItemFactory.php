<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\ClaimItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimItemFactory extends Factory
{
    protected $model = ClaimItem::class;

    public function definition()
    {
        return [
            'claim_id' => Claim::factory(),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 5, 500),
            'date' => $this->faker->dateThisMonth(),
            'receipt_number' => $this->faker->optional()->numerify('REC-####'),
            'category' => $this->faker->randomElement(['transport', 'accommodation', 'meals', 'supplies', 'other'])
        ];
    }
}
