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
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id', false);
            $table->unsignedBigInteger('technicians_id', false)->nullable();

            $table->foreign('work_order_id')
                ->references('id')
                ->on('work_orders');

            $table->foreign('technicians_id')
                ->references('id')
                ->on('technicians');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
