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
       
        if (!Schema::hasTable('warehouse_transfers'))
        {
            Schema::create('warehouse_transfers', function (Blueprint $table) {
                $table->id();
                $table->integer('from_warehouse')->default(0);
                $table->integer('to_warehouse')->default(0);
                $table->integer('product_id')->default(0);
                $table->integer('quantity')->default('0');
                $table->date('date')->nullable();
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
        Schema::dropIfExists('warehouse_transfers');
    }
};
