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
        if (!Schema::hasTable('transactions'))
        {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->string('vendor_name')->nullable();
                $table->string('user_type');
                $table->integer('account');
                $table->string('type');
                $table->float('amount')->default('0.00');
                $table->longText('description')->nullable();
                $table->date('date');
                $table->integer('payment_id')->default('0');
                $table->string('category');
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
