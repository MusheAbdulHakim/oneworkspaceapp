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
        if(!Schema::hasTable('email_templates'))
        {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('from')->nullable();
                $table->string('module_name')->nullable();
                $table->integer('created_by')->default(0);
                $table->integer('workspace_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
