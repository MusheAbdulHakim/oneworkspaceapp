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
        Schema::create('pinned_apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinned_app_category_id')->nullable()->constrained('pinned_app_categories')->onDelete('cascade');
            $table->string('module');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinned_apps');
    }
};
