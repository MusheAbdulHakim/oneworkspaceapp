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
        if(!Schema::hasTable('helpdesk_tickets'))
        {
            Schema::create('helpdesk_tickets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('ticket_id',100)->unique();
                $table->string('name');
                $table->string('email');
                $table->integer('category')->nullable();
                $table->string('subject');
                $table->string('status');
                $table->longText('description')->nullable();
                $table->longText('attachments');
                $table->string('user_id');
                $table->longText('note')->nullable();
                $table->integer('workspace')->default(0);
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
        Schema::dropIfExists('helpdesk_tickets');
    }
};
