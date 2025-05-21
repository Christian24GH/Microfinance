<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['PRESENT', 'ABSENT', 'LATE', 'ON_LEAVE', 'HOLIDAY']);
            $table->decimal('hours_worked', 5, 2)->nullable();
            $table->integer('late_minutes')->nullable();
            $table->foreignId('shift_id')->nullable()->constrained('shifts');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['employee_id', 'attendance_date']);
            $table->index(['employee_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
