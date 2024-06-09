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
        if (!Schema::hasTable('chart_of_accounts'))
        {
            Schema::create('chart_of_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('code')->default(0);
                $table->integer('type')->default(0);
                $table->integer('sub_type')->default(0);
                $table->integer('is_enabled')->default(1);
                $table->text('description')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('chart_of_accounts');
    }
};
