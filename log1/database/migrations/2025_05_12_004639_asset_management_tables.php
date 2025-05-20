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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag', 20)->unique();
            $table->enum('category', ['vehicle', 'electronic', 'furniture', 'building', 'others']);
            
            $table->enum('status', ['active', 'under repair', 'decommissioned']);
            $table->date('purchase_date')->default(now());
        });

        Schema::create('barcode', function (Blueprint $table) {
            $table->id('barcode_id');
            $table->unsignedBigInteger('asset_id');
            $table->string('barcode_value', 255);
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });

        Schema::create('location', function (Blueprint $table) {
            $table->id('location_id');
            $table->unsignedBigInteger('asset_id');
            $table->string('location_name', 255);
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });

        Schema::create('audit', function (Blueprint $table) {
            $table->id('audit_id');
            $table->unsignedBigInteger('asset_id');
            $table->date('audit_date');
            $table->string('compliance_status', 50);
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('audit');
        Schema::dropIfExists('location');
        Schema::dropIfExists('barcode');
        Schema::dropIfExists('assets');
    }
};
