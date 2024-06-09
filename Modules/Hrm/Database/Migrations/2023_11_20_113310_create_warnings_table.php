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
        if (!Schema::hasTable('warnings')) {
            Schema::create('warnings', function (Blueprint $table) {
                $table->id();
                $table->integer('warning_to');
                $table->integer('warning_by');
                $table->string('subject');
                $table->date('warning_date');
                $table->longText('description');
                $table->string('created_by');
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
        Schema::dropIfExists('warnings');
    }
};
