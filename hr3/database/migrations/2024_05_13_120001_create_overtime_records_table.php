<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('overtime_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignUuid('attendance_record_id')->nullable()->constrained('attendance_records');
            $table->foreignUuid('policy_id')->constrained('overtime_policies');
            $table->date('date');
            $table->decimal('hours', 5, 2);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED', 'PAID'])->default('PENDING');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_records');
    }
};
