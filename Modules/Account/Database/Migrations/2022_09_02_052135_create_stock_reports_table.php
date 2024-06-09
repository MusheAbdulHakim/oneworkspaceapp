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
        if (!Schema::hasTable('stock_reports'))
        {
            Schema::create('stock_reports', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id')->default('0');
                $table->integer('quantity')->default('0');
                $table->string('type');
                $table->integer('type_id')->default('0');
                $table->longText('description')->nullable();
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
        Schema::dropIfExists('stock_reports');
    }
};
