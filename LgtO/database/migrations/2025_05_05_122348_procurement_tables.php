<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->unsignedBigInteger('requested_by')->nullable(); // User or Department who requested
            $table->string('subject');
            $table->text('description')->nullable();
            $table->enum('subject_type', ['Service', 'Asset']);
            $table->integer('quantity');
            $table->string('unit');
            $table->date('due_date')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Fullfilled'])->default('Pending'); // pending, approved, rejected, fulfilled
            $table->timestamps();

            // Optional: foreign key if you have a users table
            // $table->foreign('requested_by')->references('id')->on('users');
        });
        
        Schema::create('procurement_bids', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('prc_request_id');
            $table->string('agreement_text')->nullable();
            $table->decimal('offer_price', 8, 2);
            $table->enum('status', ['Accept', 'Pending','Cancel'])->default('Pending');
            $table->timestamps();

            $table->foreign('prc_request_id')
                ->references('id')
                ->on('procurement_requests')
                ->onDelete('cascade');
            
            /*
            $table->foreign('supplier_id')
                ->references('id')
                ->on('vendors')
                ->onDelete('cascade');
            */
        });

        Schema::create('procurement_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prc_bid_id');
            $table->decimal('invoice_amount', 10, 2);
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->enum('invoice_status', [
                'Pending',
                'Sent',
                'Received',
                'Approved',
                'Disputed',
                'Cancelled',
                'Closed'
            ])->default('Pending');
            $table->enum('payment_status', ['Unpaid', 'Paid'])->default('Unpaid');
            $table->timestamps();

            $table->foreign('prc_bid_id')->references('id')->on('procurement_bids')->onDelete('cascade');
        });

        Schema::create('procurement_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('receipt_number')->unique();
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('procurement_invoices')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('procurement_receipts');
        Schema::dropIfExists('procurement_invoices');
        Schema::dropIfExists('procurement_bids');
        Schema::dropIfExists('procurement_requests');
    }
};
