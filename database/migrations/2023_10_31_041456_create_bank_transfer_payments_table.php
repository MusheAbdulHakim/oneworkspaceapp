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
        if(!Schema::hasTable('bank_transfer_payments'))
        {
            Schema::create('bank_transfer_payments', function (Blueprint $table) {
                $table->id();
                $table->string('order_id');
                $table->integer('user_id');
                $table->longText('request');
                $table->string('status');
                $table->string('type');
                $table->float('price')->default(0);
                $table->string('price_currency')->default('USD');
                $table->string('attachment')->nullable();
                $table->integer('created_by');
                $table->integer('workspace')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transfer_payments');
    }
};
