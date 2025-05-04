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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->string('description', 50)->nullable();
            $table->enum('maintenance_type', ['preventive', 'corrective']);
            $table->string('location', 50)->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled']);
            $table->date('created_at')->default(now());
            $table->date('updated_at');

            $table->unsignedBigInteger('asset_id', false);
            $table->unsignedBigInteger('schedule_id', false);
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
