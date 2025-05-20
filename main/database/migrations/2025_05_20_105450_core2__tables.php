<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id(); // id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT
            $table->string('recipient_email', 255);
            $table->string('subject', 255);
            $table->text('message')->nullable();
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->timestamp('send_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            // No timestamps() because your table doesn't have created_at or updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
};
