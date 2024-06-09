<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories'))
        {
            Schema::create('categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('type')->default(0);
                $table->string('color')->default('#fc544b');
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
        Schema::dropIfExists('categories');
    }
}
