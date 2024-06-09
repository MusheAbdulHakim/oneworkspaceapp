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
        if (!Schema::hasTable('travels')) {
            Schema::create('travels', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id')->nullable();
                $table->integer('user_id');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('purpose_of_visit');
                $table->string('place_of_visit');
                $table->longText('description');
                $table->integer('workspace')->nullable();
                $table->string('created_by');
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
        Schema::dropIfExists('travels');
    }
};
