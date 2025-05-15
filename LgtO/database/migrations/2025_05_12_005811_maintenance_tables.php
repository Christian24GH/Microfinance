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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('maintenance_date');
        });

        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
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
            $table->foreign('created_by')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id', false);
            $table->unsignedBigInteger('technicians_id', false)->nullable();

            $table->foreign('work_order_id')
                ->references('id')
                ->on('work_orders');

            $table->foreign('technicians_id')
                ->references('id')
                ->on('accounts');
        });

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

        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maintenance_id');
            $table->string('description');
            $table->timestamps();

            $table->foreign('maintenance_id')->references('id')->on('maintenance')->onDelete('cascade');
        });

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
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('parts_inventory');
        Schema::dropIfExists('maintenance');
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('schedules');
    }
};
