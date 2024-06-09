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
        if(!Schema::hasTable('labels'))
        {
            Schema::create('labels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('color');
                $table->integer('pipeline_id');
                $table->integer('created_by');
                $table->integer('workspace_id')->default(0);
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
        Schema::dropIfExists('labels');
    }
};
