<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->id('warehouse_id');
            $table->string('name', 255)->unique();
            $table->string('location', 255);
            $table->integer('capacity');
            $table->unsignedBigInteger('manager_id');
            $table->timestamps();

            $table->foreign('manager_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('supplier', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('name', 255);
            $table->string('contact_no', 15);
            $table->string('email')->unique();
            $table->text('address');
            $table->timestamps();
        });

        Schema::create('order', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('vendor_name');
            $table->date('order_date');
            $table->enum('status', ['pending', 'approved', 'shipped', 'received', 'cancelled']);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('procurement_invoices')->onDelete('cascade');
            //$table->foreign('supplier_id')->references('supplier_id')->on('supplier')->onDelete('cascade');
        });

        Schema::create('shipment', function (Blueprint $table) {
            $table->id('shipment_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('order_id');
            $table->date('ship_date');
            $table->string('carrier', 255);
            $table->string('tracking_no', 100)->unique();
            $table->enum('delivery_status', ['pending', 'shipped', 'in_transit', 'delivered', 'cancelled']);
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('order')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouse')->onDelete('cascade');
        });

        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('shipment_id');
            $table->string('item_name', 255);
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('shipment_id')->references('shipment_id')->on('shipment')->onDelete('cascade');
           
        });

        Schema::create('quality_check', function (Blueprint $table) {
            $table->id('qc_id');
            $table->unsignedBigInteger('inventory_id');
            $table->date('check_date');
            $table->enum('result', ['pass', 'fail']);
            $table->timestamps();

            $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');
        });

        Schema::create('dockschedule', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->timestamp('timeslot');
            $table->timestamps();

            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouse')->onDelete('cascade');
        });

        Schema::create('lastmile_delivery', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->unsignedBigInteger('shipment_id');
            $table->string('delivery_person', 255);
            $table->string('vehicle_no', 50);
            $table->timestamp('departure_time')->nullable();
            $table->timestamp('arrival_time')->nullable();
            $table->timestamps();

            $table->foreign('shipment_id')->references('shipment_id')->on('shipment')->onDelete('cascade');
        });

        Schema::create('rfid_tag', function (Blueprint $table) {
            $table->id('tag_id');
            $table->unsignedBigInteger('inventory_id');
            $table->string('barcode', 100)->unique();
            $table->timestamp('scanned_time')->nullable();
            $table->timestamps();

            $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');
        });

        Schema::create('wrh_report', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('report_type', 100);
            $table->date('generated_date');
            $table->timestamps();

            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouse')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('wrh_report');
        Schema::dropIfExists('rfid_tag');
        Schema::dropIfExists('lastmile_delivery');
        Schema::dropIfExists('dockschedule');
        Schema::dropIfExists('quality_check');
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('shipment');
        Schema::dropIfExists('order');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('warehouse');
    }
};
