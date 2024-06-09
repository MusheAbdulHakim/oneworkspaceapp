<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('plans'))
        {
            Schema::create('plans', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('number_of_user')->nullable();
                $table->integer('custom_plan')->default(0);
                $table->integer('active')->default(1);
                $table->integer('is_free_plan')->default(0);
                $table->double('package_price_monthly')->default(0);
                $table->double('package_price_yearly')->default(0);
                $table->double('price_per_user_monthly')->default(0);
                $table->double('price_per_user_yearly')->default(0);
                $table->integer('price_per_workspace_monthly')->default(0);
                $table->integer('price_per_workspace_yearly')->default(0);
                $table->longtext('modules')->nullable();
                $table->integer('trial')->default(0);
                $table->string('trial_days')->nullable();
                $table->string('number_of_workspace')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
