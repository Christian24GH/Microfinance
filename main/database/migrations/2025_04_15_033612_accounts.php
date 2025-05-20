<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void
    {
        Schema::create('client_info', function (Blueprint $table) {
            $table->string('client_id', 50)->primary();
            $table->string('first_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Seperated', 'Widowed'])->nullable();
            $table->date('birthdate')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email', 40);
            $table->text('address')->nullable();
            $table->string('barangay', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('province', 50)->nullable();
            $table->date('registration_date')->default(DB::raw('CURDATE()'));
            $table->string('status', 20)->default('active');
        });

        Schema::create('accounts', function(Blueprint $table){
            $table->id();
            $table->string('fullname', 255);
            $table->string('username', 255)->unique();
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
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('employee_info')->nullable();

            //Foreign
            $table->foreign('client_id')->references('client_id')->on('client_info')->onDelete('cascade');

        });

        
        
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('client_info');
    }
    
};
