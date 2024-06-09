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
        if (!Schema::hasTable('bill_products'))
        {
            Schema::create('bill_products', function (Blueprint $table)
            {
                $table->id();
                $table->integer('bill_id');
                $table->string('product_type')->nullable();
                $table->integer('product_id');
                $table->integer('quantity');
                $table->string('tax')->nullable();
                $table->float('discount')->default('0.00');
                $table->float('price')->default('0.00');
                $table->longText('description')->nullable();
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
        Schema::dropIfExists('bill_products');
    }
};
