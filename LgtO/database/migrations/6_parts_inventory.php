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
        Schema::create('parts_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('part_name');
            $table->string('part_number')->unique();
            $table->string('description')->nullable();
            $table->enum('category', ['electrical', 'mechanical']);
            $table->bigInteger('quantity_in_stock', false, true);
            $table->enum('unit', ['pc', 'liters']);
            $table->bigInteger('reorder_level', false, true);
            $table->decimal('unit_cost', 8, 2);
            $table->enum('status', ['active', 'discontinued']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts_inventory');
    }
};
