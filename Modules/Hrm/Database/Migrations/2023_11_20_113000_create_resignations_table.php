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
        if (!Schema::hasTable('resignations')) {
            Schema::create('resignations', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id')->nullable();
                $table->integer('user_id');
                $table->date('resignation_date');
                $table->date('last_working_date');
                $table->longText('description');
                $table->integer('created_by');
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('resignations');
    }
};
