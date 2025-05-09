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
    }

    public function down()
    {

       Schema::dropIfExists('procurement_requests');
    }
};
