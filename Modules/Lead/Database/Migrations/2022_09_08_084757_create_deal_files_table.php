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
        if(!Schema::hasTable('deal_files'))
        {
            Schema::create('deal_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('deal_id');
                $table->string('file_name');
                $table->string('file_path');
                $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
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
        Schema::dropIfExists('deal_files');
    }
};
