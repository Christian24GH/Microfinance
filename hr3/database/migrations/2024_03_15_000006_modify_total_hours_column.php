<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('time_entries', function (Blueprint $table) {
            $table->decimal('total_hours', 8, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('time_entries', function (Blueprint $table) {
            $table->decimal('total_hours', 8, 2)->change();
        });
    }
};
