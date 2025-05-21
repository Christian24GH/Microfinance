<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clocking_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->dateTimeTz('clocking_time');
            $table->enum('clocking_type', ['IN', 'OUT', 'BREAK_START', 'BREAK_END']);
            $table->string('device_id')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('timezone')->default('UTC');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['employee_id', 'clocking_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clocking_records');
    }
};
