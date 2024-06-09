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
        if (!Schema::hasTable('transaction_lines')) {
            Schema::create('transaction_lines', function (Blueprint $table) {
                $table->id();
                $table->integer('account_id')->nullable();
                $table->string('reference')->nullable();
                $table->integer('reference_id')->default('0');
                $table->integer('reference_sub_id')->default('0');
                $table->date('date');
                $table->double('credit', 15, 2)->default('0.00');
                $table->double('debit', 15, 2)->default('0.00');
                $table->integer('workspace')->default('0');
                $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('transaction_lines');
    }
};
