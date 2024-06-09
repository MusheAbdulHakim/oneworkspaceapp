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
        if (!Schema::hasTable('payments'))
        {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->float('amount',15,2)->default('0.00');
                $table->integer('account_id');
                $table->integer('vendor_id');
                $table->longText('description');
                $table->integer('category_id');
                $table->string('recurring')->nullable();
                $table->integer('payment_method');
                $table->string('reference');
                $table->string('add_receipt')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
