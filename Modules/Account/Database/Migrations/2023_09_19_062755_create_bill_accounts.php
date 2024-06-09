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
        if (!Schema::hasTable('bill_accounts'))
        {
            Schema::create('bill_accounts', function (Blueprint $table) {
                $table->id();
                $table->integer('chart_account_id')->default('0.00');
                $table->float('price')->default('0.00');
                $table->string('description')->nullable();
                $table->string('type')->nullable();
                $table->integer('ref_id')->default('0');
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('bill_accounts');
    }
};
