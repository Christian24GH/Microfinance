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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maintenance_id');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled']);
            $table->decimal('duration_hours', 5, 2)->nullable();
            $table->date('log_date');
            $table->timestamps();

            $table->foreign('maintenance_id')->references('id')->on('maintenance')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
