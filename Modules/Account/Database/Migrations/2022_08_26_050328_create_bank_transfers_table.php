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
        if (!Schema::hasTable('bank_transfers'))
        {
            Schema::create('bank_transfers', function (Blueprint $table) {
                $table->id();
                $table->integer('from_account')->default('0');
                $table->integer('to_account')->default('0');
                $table->float('amount',15,2)->default('0');
                $table->date('date');
                $table->integer('payment_method')->default('0');
                $table->string('reference')->nullable();
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
        Schema::dropIfExists('bank_transfers');
    }
};
