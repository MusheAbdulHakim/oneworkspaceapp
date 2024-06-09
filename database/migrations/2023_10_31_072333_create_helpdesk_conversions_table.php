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
        if(!Schema::hasTable('helpdesk_conversions'))
        {
            Schema::create('helpdesk_conversions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('ticket_id');
                $table->text('description');
                $table->text('attachments');
                $table->text('sender');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helpdesk_conversions');
    }
};
