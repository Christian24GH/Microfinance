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
        // client_documents
        Schema::create('client_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('client_docu_id')->autoIncrement();
            $table->enum('docu_type', ["Driver\'s License", 'Passport', 'SSS', 'UMID', 'PhilID'])->nullable();
            $table->binary('document_one')->nullable();
            $table->binary('document_two')->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('client_docu_id');
        });

        // client_employment
        Schema::create('client_employment', function (Blueprint $table) {
            $table->unsignedBigInteger('client_emp_id')->autoIncrement();
            $table->string('employer_name', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('position', 50)->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('client_emp_id');
        });

        // client_financial_info
        Schema::create('client_financial_info', function (Blueprint $table) {
            $table->unsignedBigInteger('client_fin_id')->autoIncrement();
            $table->enum('source_of_funds', ['Employment', 'Savings', 'Allowance', 'Business', 'Pension'])->nullable();
            $table->integer('monthly_income')->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('client_fin_id');
        });

        // client_loan_limit
        Schema::create('client_loan_limit', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->integer('ll_amount')->nullable();
            $table->integer('ll_month')->nullable();
            $table->integer('ll_interest')->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('id');
        });

        // client_references
        Schema::create('client_references', function (Blueprint $table) {
            $table->unsignedBigInteger('client_ref_id')->autoIncrement();
            $table->string('fr_first_name', 50)->nullable();
            $table->string('fr_last_name', 50)->nullable();
            $table->enum('fr_relationship', ['Mother', 'Father', 'Siblings', 'Friends', 'Colleague', 'Relatives'])->nullable();
            $table->integer('fr_contact_number')->nullable();
            $table->string('sr_first_name', 50)->nullable();
            $table->string('sr_last_name', 50)->nullable();
            $table->enum('sr_relationship', ['Mother', 'Father', 'Siblings', 'Friends', 'Colleague', 'Relatives'])->nullable();
            $table->integer('sr_contact_number')->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('client_ref_id');
        });

        // client_status
        Schema::create('client_status', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('c_status', 50)->nullable();
            $table->string('l_status', 50)->nullable();
            $table->string('r_status', 50)->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('id');
        });

        // loan_info
        Schema::create('loan_info', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_id')->autoIncrement();
            $table->integer('amount')->nullable();
            $table->integer('month')->nullable();
            $table->integer('terms')->nullable();
            $table->enum('purpose', ['Tuition', 'Bills', 'Emergency', 'Online Shopping'])->nullable();
            $table->integer('interest')->nullable();
            $table->integer('total')->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('loan_id');
        });

        // loan_restrc
        Schema::create('loan_restrc', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->integer('r_amount')->nullable();
            $table->integer('r_month')->nullable();
            $table->integer('r_interest')->nullable();
            $table->string('client_id', 50)->nullable();
            $table->primary('id');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_restrc');
        Schema::dropIfExists('loan_info');
        Schema::dropIfExists('client_status');
        Schema::dropIfExists('client_references');
        Schema::dropIfExists('client_loan_limit');
        Schema::dropIfExists('client_financial_info');
        Schema::dropIfExists('client_employment');
        Schema::dropIfExists('client_documents');
    }
};
