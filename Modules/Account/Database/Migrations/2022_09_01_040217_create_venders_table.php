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
        if (!Schema::hasTable('vendors'))
        {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->integer('vendor_id');
                $table->integer('user_id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('contact')->nullable();
                $table->string('tax_number')->nullable();
                $table->string('billing_name')->nullable();
                $table->string('billing_country')->nullable();
                $table->string('billing_state')->nullable();
                $table->string('billing_city')->nullable();
                $table->string('billing_phone')->nullable();
                $table->string('billing_zip')->nullable();
                $table->text('billing_address')->nullable();
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
        Schema::dropIfExists('vendors');
    }
};
