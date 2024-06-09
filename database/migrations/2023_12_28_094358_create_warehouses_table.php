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
        if (!Schema::hasTable('warehouses'))
        {
                Schema::create('warehouses', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->text('address');
                    $table->string('city');
                    $table->string('city_zip');
                    $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('warehouses');
    }
};
