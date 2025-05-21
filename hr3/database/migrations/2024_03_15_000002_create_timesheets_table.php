<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->string('status')->default('draft'); // draft, pending, approved, rejected
            $table->text('notes')->nullable();
            $table->foreignId('current_approver_id')->nullable()->constrained('users');
            $table->integer('current_workflow_stage')->default(1);
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
