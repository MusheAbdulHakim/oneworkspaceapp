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
        if(!Schema::hasTable('user_active_modules'))
        {
            Schema::create('user_active_modules', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('module');
                $table->timestamps();

                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_active_modules');
    }
};
