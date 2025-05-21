<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('employees', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->string('position')->nullable();
        });
    }
};
