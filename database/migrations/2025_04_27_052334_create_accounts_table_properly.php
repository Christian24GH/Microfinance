<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('Accounts')) {
            Schema::create('Accounts', function (Blueprint $table) {
                $table->id();
                $table->string('fullname', 255)->nullable();
                $table->string('email', 255)->nullable();
                $table->string('password', 255)->nullable();
                $table->enum('role', [
                    'HRSupervisor',
                    'FinanceOfficer',
                    'AuditOfficer',
                    'MaintenanceStaff',
                    'MaintenanceAdmin',
                    'ProjectManager',
                    'TeamMember',
                    'AssetAdmin',
                    'AssetStaff',
                    'AssetAnalyst',
                    'WarehouseManager',
                    'InventoryStaff',
                    'Supplier',
                    'ProcurementAdministrator',
                    'QualityInspector',
                    'ProcurementAnalyst',
                    'CommunicationOfficer',
                    'PayrollOfficer',
                    'ADMIN',
                    'EMPLOYEE',
                    'HRAdministrator',
                    'Manager/Supervisor'
                ])->nullable();
                $table->rememberToken()->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('Accounts');
    }
};
