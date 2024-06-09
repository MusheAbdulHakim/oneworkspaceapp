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
        if (!Schema::hasTable('bank_accounts'))
        {
            Schema::create('bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('holder_name');
                $table->integer('chart_account_id')->default('0');
                $table->string('bank_name');
                $table->string('account_number');
                $table->float('opening_balance', 15, 2)->default('0.00');
                $table->string('contact_number');
                $table->text('bank_address');
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
        Schema::dropIfExists('bank_accounts');
    }
};
