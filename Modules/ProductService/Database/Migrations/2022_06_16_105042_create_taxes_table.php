<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('taxes'))
        {
            Schema::create('taxes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->text('rate');
                $table->integer('created_by')->default('0');
                $table->integer('workspace_id')->default('0');
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
        Schema::dropIfExists('taxes');
    }
}
