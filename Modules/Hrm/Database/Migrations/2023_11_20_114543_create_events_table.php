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
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('branch_id');
                $table->longText('department_id');
                $table->longText('employee_id');
                $table->string('title');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('color');
                $table->longText('description')->nullable();
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
        Schema::dropIfExists('events');
    }
};
