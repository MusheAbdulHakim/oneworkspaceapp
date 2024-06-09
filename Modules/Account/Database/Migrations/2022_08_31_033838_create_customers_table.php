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
        if (!Schema::hasTable('customers'))
        {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->integer('customer_id');
                $table->integer('user_id')->nullable();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('password')->nullable();
                $table->string('contact')->nullable();
                $table->string('tax_number')->nullable();
                $table->string('billing_name');
                $table->string('billing_country');
                $table->string('billing_state');
                $table->string('billing_city');
                $table->string('billing_phone');
                $table->string('billing_zip');
                $table->text('billing_address');
                $table->string('shipping_name')->nullable();
                $table->string('shipping_country')->nullable();
                $table->string('shipping_state')->nullable();
                $table->string('shipping_city')->nullable();
                $table->string('shipping_phone')->nullable();
                $table->string('shipping_zip')->nullable();
                $table->text('shipping_address')->nullable();
                $table->string('lang')->default('en');
                $table->float('balance')->default('0.00');
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->rememberToken();
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
        Schema::dropIfExists('customers');
    }
};
