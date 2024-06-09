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
        if(!Schema::hasTable('purchase_attachments'))
        {
            Schema::create('purchase_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('purchase_id');
                $table->string('file_name');
                $table->string('file_path');
                $table->string('file_size');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_attachments');
    }
};
