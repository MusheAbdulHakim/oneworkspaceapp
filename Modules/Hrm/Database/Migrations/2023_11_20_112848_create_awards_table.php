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
        if (!Schema::hasTable('awards')) {
            Schema::create('awards', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id')->nullable();
                $table->integer('user_id');
                $table->string('award_type');
                $table->date('date');
                $table->string('gift');
                $table->longText('description');
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
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
        Schema::dropIfExists('awards');
    }
};
