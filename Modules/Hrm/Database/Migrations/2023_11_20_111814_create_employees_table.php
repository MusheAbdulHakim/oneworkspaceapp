<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();

                $table->integer('user_id');
                $table->string('name');
                $table->date('dob')->nullable();
                $table->string('gender');
                $table->string('phone')->nullable();
                $table->longText('address');
                $table->string('email');
                $table->string('password')->nullable();

                $table->string('employee_id');
                $table->integer('branch_id');
                $table->integer('department_id');
                $table->integer('designation_id');
                $table->string('company_doj')->nullable();
                $table->string('documents')->nullable();

                $table->string('account_holder_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('bank_identifier_code')->nullable();
                $table->string('branch_location')->nullable();
                $table->string('tax_payer_id')->nullable();
                $table->integer('salary_type')->nullable();
                $table->integer('salary')->nullable();
                $table->integer('is_active')->default('1');
                $table->integer('workspace')->nullable();
                $table->integer('created_by');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
