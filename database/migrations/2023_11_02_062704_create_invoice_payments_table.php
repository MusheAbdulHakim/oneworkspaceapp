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
        if(!Schema::hasTable('invoice_payments'))
        {
            Schema::create('invoice_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id');
                $table->date('date');
                $table->float('amount')->default('0.00');
                $table->integer('account_id')->nullable();
                $table->integer('payment_method');
                $table->string('reference')->nullable();
                $table->text('description')->nullable();
                $table->string('order_id')->nullable();
                $table->string('currency')->nullable();
                $table->string('txn_id')->nullable();
                $table->string('payment_type')->default('Manually');
                $table->string('receipt')->nullable();
                $table->string('add_receipt')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
