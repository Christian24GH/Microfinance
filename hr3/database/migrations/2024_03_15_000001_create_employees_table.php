<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('name');
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->foreignId('position_id')->constrained('positions')->onDelete('restrict');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
