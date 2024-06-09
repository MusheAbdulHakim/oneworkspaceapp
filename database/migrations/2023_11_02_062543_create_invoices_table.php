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
        if(!Schema::hasTable('invoices'))
        {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('invoice_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->date('issue_date');
                $table->date('due_date');
                $table->date('send_date')->nullable();
                $table->integer('category_id');
                $table->integer('status')->default('0');
                $table->string('invoice_module')->nullable();
                $table->integer('shipping_display')->default('1');
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default('0');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
