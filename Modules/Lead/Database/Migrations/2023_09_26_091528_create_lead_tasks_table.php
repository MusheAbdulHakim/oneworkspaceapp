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
        if(!Schema::hasTable('lead_tasks'))
        {
            Schema::create('lead_tasks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id');
                $table->string('name');
                $table->date('date');
                $table->time('time');
                $table->string('priority');
                $table->string('status');
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
                $table->integer('workspace');
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
        Schema::dropIfExists('lead_tasks');
    }
};
