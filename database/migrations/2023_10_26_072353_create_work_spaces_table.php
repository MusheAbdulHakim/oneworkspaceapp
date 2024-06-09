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
        if(!Schema::hasTable('work_spaces'))
        {
            Schema::create('work_spaces', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('status')->default('active');
                $table->string('slug')->nullable();
                $table->integer('is_disable')->default(1);
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_spaces');
    }
};
