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
        Schema::create('url_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->text('url')->nullable();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('color')->nullable();
            $table->longText('note')->nullable();
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->nullable();
            $table->integer('user_id');
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_bookmarks');
    }
};
