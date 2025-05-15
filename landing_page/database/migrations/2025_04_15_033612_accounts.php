<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void
    {
        Schema::create('accounts', function(Blueprint $table){
            $table->id();
            $table->string('fullname', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('role', [
                'Client',
                'HR Supervisor',
                'Finance Officer',
                'Audit Officer',
                'Maintenance Staff',
                'Technician',
                'Maintenance Admin',
                'Project Manager',
                'Asset Admin',
                'Asset Staff',
                'Asset Analyst',
                'Warehouse Manager',
                'Inventory Staff',
                'Supplier',
                'Procurement Administrator',
                'Quality Inspector',
                'Procurement Analyst',
                'Communication Officer',
                'Payroll Officer',
                'Super Admin',
                'Employee',
                'HR Administrator',
                'Manager/Supervisor',
                'Team Member',
                'Team Leader',
                'Loan Officer'
            ]);
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
    
};
