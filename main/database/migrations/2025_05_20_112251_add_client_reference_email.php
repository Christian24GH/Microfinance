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
        Schema::table('client_references', function (Blueprint $table) {
            $table->string('fr_email')->nullable()->default('NULL');
            $table->string('sr_email')->nullable()->default('NULL');;
            $table->string('email', 40);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_references', function (Blueprint $table) {
            $table->dropColumn('fr_email');
            $table->dropColumn('sr_email');
            $table->dropColumn('email');
        });
    }
};
