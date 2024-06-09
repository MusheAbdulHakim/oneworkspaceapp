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
        if (!Schema::hasTable('bill_payments'))
        {
            Schema::create('bill_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('bill_id');
                $table->date('date');
                $table->float('amount')->default('0.00');
                $table->integer('account_id');
                $table->integer('payment_method');
                $table->string('reference');
                $table->string('add_receipt')->nullable();
                $table->longText('description');
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
        Schema::dropIfExists('bill_payments');
    }
};
