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
        if (!Schema::hasTable('overtimes')) {
            Schema::create('overtimes', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id');
                $table->string('title');
                $table->string('type')->nullable();
                $table->integer('number_of_days');
                $table->integer('hours');
                $table->integer('rate');
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
        Schema::dropIfExists('overtimes');
    }
};
