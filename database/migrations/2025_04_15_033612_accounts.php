<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Accounts', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
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
            ]);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Accounts');
    }
};
