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
        if (!Schema::hasTable('invoice_products'))
        {
            Schema::create('invoice_products', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id');
                $table->string('product_type')->nullable();
                $table->integer('product_id');
                $table->integer('quantity');
                $table->string('tax')->nullable();
                $table->float('discount')->default('0.00');
                $table->longText('description')->nullable();
                $table->float('price')->default('0.00');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_products');
    }
};
