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
        if (!Schema::hasTable('side_menu_builders')) {
            Schema::create('side_menu_builders', function (Blueprint $table) {
                $table->id();
                $table->string('menu_type')->nullable();
                $table->string('name')->nullable();
                $table->string('icon')->nullable();
                $table->string('link_type')->nullable();
                $table->string('link')->nullable();
                $table->integer('position')->nullable()->default('0');
                $table->integer('parent_id')->default('0');
                $table->string('window_type')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('side_menu_builders');
    }
};
