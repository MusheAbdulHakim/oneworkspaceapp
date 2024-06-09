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
        if (!Schema::hasTable('purchase_payments'))
        {
            Schema::create('purchase_payments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('purchase_id');
                $table->date('date');
                $table->float('amount')->default('0.00');
                $table->integer('account_id')->nullable();
                $table->integer('payment_method');
                $table->string('reference')->nullable();
                $table->text('description')->nullable();
                $table->string('add_receipt')->nullable();
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('purchase_payments');
    }
};
