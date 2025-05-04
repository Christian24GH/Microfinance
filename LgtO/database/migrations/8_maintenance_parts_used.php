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
        Schema::create('parts_used', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maintenance_log_id');
            $table->unsignedBigInteger('part_id');
            $table->integer('quantity_used');
            $table->enum('unit', ['pc', 'liters']);

            $table->foreign('maintenance_log_id')->references('id')->on('maintenance_logs')->onDelete('cascade');
            $table->foreign('part_id')->references('id')->on('parts_inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts_used');
    }
};
