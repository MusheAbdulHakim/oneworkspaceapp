<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('languages'))
        {
            Schema::create('languages', function (Blueprint $table) {
                $table->id();
                $table->string('code');
                $table->string('name');
                $table->string('status')->default(1);
                $table->timestamps();
            });

            // Call the seeder
            Artisan::call('db:seed', [
                '--class' => 'LanguageTableSeeder',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
