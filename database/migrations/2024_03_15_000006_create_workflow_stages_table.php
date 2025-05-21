<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('stage_order');
            $table->enum('approver_type', ['employee', 'role', 'department']);
            $table->unsignedBigInteger('approver_id');
            $table->boolean('is_final')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['workflow_id', 'stage_order']);
            $table->index(['workflow_id', 'is_final']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_stages');
    }
};
