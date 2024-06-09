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
        if (!Schema::hasTable('purchase_debit_notes'))
        {
            Schema::create('purchase_debit_notes', function (Blueprint $table) {
                $table->id();
                $table->integer('purchase')->default('0');
                $table->integer('vendor')->default('0');
                $table->float('amount', 15, 2)->default('0.00');
                $table->date('date');
                $table->longText('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_debit_notes');
    }
};
