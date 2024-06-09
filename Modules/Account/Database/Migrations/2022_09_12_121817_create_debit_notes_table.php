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
        if (!Schema::hasTable('debit_notes'))
        {
            Schema::create('debit_notes', function (Blueprint $table) {
                $table->id();
                $table->integer('bill')->default('0');
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debit_notes');
    }
};
