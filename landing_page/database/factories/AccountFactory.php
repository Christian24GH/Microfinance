<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accounts>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password
            'role' => $this->faker->randomElement([
                'HRSupervisor', 'FinanceOfficer', 'AuditOfficer', 'MaintenanceStaff',
                'MaintenanceAdmin', 'ProjectManager', 'TeamMember', 'AssetAdmin',
                'AssetStaff', 'AssetAnalyst', 'WarehouseManager', 'InventoryStaff',
                'Supplier', 'ProcurementAdministrator', 'QualityInspector',
                'ProcurementAnalyst', 'CommunicationOfficer', 'PayrollOfficer',
                'ADMIN', 'EMPLOYEE', 'HRAdministrator', 'Manager/Supervisor'
            ]),
        ];
    }
}
