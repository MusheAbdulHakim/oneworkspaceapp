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
        if (!Schema::hasTable('tax_brackets')) {
            Schema::create('tax_brackets', function (Blueprint $table) {
                $table->id();
                $table->float('from', 30, 2)->nullable();
                $table->float('to', 30, 2)->nullable();
                $table->float('fixed_amount', 30, 2)->nullable();
                $table->float('percentage', 30, 2)->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
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
        Schema::dropIfExists('tax_brackets');
    }
};
