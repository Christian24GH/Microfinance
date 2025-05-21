<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('timesheet_id')->nullable()->constrained('timesheets')->onDelete('set null')->after('employee_id');
            $table->date('date');
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_entries');
    }
};
