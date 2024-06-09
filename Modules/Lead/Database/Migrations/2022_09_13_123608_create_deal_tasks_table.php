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
        if(!Schema::hasTable('deal_tasks'))
        {
            Schema::create('deal_tasks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('deal_id');
                $table->string('name');
                $table->date('date');
                $table->time('time');
                $table->string('priority');
                $table->string('status');
                $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
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
        Schema::dropIfExists('deal_tasks');
    }
};
