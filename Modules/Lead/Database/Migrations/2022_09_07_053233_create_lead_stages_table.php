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
        if(!Schema::hasTable('lead_stages'))
        {
            Schema::create('lead_stages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('pipeline_id');
                $table->integer('created_by');
                $table->integer('order')->default(0);
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
        Schema::dropIfExists('lead_stages');
    }
};
