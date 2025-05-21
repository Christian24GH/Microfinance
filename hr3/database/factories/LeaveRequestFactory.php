<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+5 days');
        $totalDays = $startDate->diff($endDate)->days + 1;

        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => LeaveType::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'cancelled']),
            'rejection_reason' => function (array $attributes) {
                return $attributes['status'] === 'rejected' ? $this->faker->sentence() : null;
            },
            'approved_by' => function (array $attributes) {
                return $attributes['status'] === 'approved' ? Employee::factory() : null;
            },
            'approved_at' => function (array $attributes) {
                return $attributes['status'] === 'approved' ? $this->faker->dateTimeBetween('-1 month', 'now') : null;
            }
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'rejection_reason' => null,
                'approved_by' => null,
                'approved_at' => null
            ];
        });
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
                'rejection_reason' => null,
                'approved_by' => Employee::factory(),
                'approved_at' => $this->faker->dateTimeBetween('-1 month', 'now')
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'rejection_reason' => $this->faker->sentence(),
                'approved_by' => null,
                'approved_at' => null
            ];
        });
    }

    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'rejection_reason' => null,
                'approved_by' => null,
                'approved_at' => null
            ];
        });
    }
}
