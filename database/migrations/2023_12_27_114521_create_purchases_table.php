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
        if (!Schema::hasTable('purchases'))
        {
            Schema::create('purchases', function (Blueprint $table) {
                $table->id();
                $table->string('purchase_id')->default('0');
                $table->integer('user_id')->nullable();
                $table->integer('vender_id')->nullable();
                $table->string('vender_name')->nullable();
                $table->integer('warehouse_id');
                $table->date('purchase_date');
                $table->integer('purchase_number')->default('0');
                $table->integer('status')->default('0');
                $table->integer('shipping_display')->default('1');
                $table->date('send_date')->nullable();
                $table->integer('discount_apply')->default('0');
                $table->integer('category_id');
                $table->string('purchase_module');
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default('0');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
