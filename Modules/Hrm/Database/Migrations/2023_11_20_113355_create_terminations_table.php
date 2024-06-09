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
        if (!Schema::hasTable('terminations')) {
            Schema::create('terminations', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id')->nullable();
                $table->integer('user_id');
                $table->date('notice_date');
                $table->date('termination_date');
                $table->string('termination_type');
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
        Schema::dropIfExists('terminations');
    }
};
