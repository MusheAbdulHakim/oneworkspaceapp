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
        if(!Schema::hasTable('deals'))
        {
            Schema::create('deals', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->float('price')->nullable();
                $table->integer('pipeline_id');
                $table->integer('stage_id');
                $table->string('sources')->nullable();
                $table->string('products')->nullable();
                $table->text('notes')->nullable();
                $table->string('labels')->nullable();
                $table->string('status')->nullable();
                $table->integer('order')->default(0);
                $table->string('phone')->nullable();
                $table->integer('created_by');
                $table->integer('is_active')->default(1);
                $table->integer('workspace_id')->default(0);
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
        Schema::dropIfExists('deals');
    }
};
