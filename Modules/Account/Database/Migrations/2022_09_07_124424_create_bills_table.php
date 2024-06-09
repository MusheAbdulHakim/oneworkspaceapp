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
        if (!Schema::hasTable('bills'))
        {
            Schema::create('bills', function (Blueprint $table) {
                $table->id();
                $table->string('bill_id')->default('0');
                $table->integer('vendor_id');
                $table->integer('user_id')->nullable();
                $table->date('bill_date');
                $table->date('due_date');
                $table->integer('order_number')->default('0');
                $table->integer('status')->default('0');
                $table->integer('bill_shipping_display')->default('1');
                $table->date('send_date')->nullable();
                $table->integer('discount_apply')->default('0');
                $table->string('bill_module')->default('account');
                $table->integer('category_id');
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
        Schema::dropIfExists('bills');
    }
};
