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
        Schema::table('employee_info', function (Blueprint $table) {
            $table->enum('sex', ['Male', 'Female'])->nullable()->after('lastname');
            $table->enum('civil_status', ['Single', 'Married', 'Separated', 'Widowed'])->nullable()->after('sex');
            $table->date('birthdate')->nullable()->after('civil_status');
            $table->string('contact_number')->nullable()->after('birthdate');
            $table->string('email', 100)->nullable()->after('contact_number');
            $table->text('address')->nullable()->after('email');
            $table->string('barangay', 50)->nullable()->after('address');
            $table->string('city', 50)->nullable()->after('barangay');
            $table->string('province', 50)->nullable()->after('city');
            $table->date('hire_date')->nullable()->after('province');
            $table->string('status', 20)->default('active')->after('hire_date');
            $table->timestamp('created_at')->useCurrent()->after('status');
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->after('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('employee_info', function (Blueprint $table) {
            $table->dropColumn([
                'sex',
                'civil_status',
                'birthdate',
                'contact_number',
                'email',
                'address',
                'barangay',
                'city',
                'province',
                'hire_date',
                'status',
                'created_at',
                'updated_at'
            ]);
        });
    }
};
