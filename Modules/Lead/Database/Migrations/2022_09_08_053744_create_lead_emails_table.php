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
        if(!Schema::hasTable('lead_emails'))
        {
            Schema::create('lead_emails', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id');
                $table->string('to');
                $table->string('subject');
                $table->longText('description')->nullable();
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
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
        Schema::dropIfExists('lead_emails');
    }
};
