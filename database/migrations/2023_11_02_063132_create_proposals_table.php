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
        if (!Schema::hasTable('proposals'))
        {
            Schema::create('proposals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('proposal_id');
                $table->unsignedBigInteger('customer_id');
                $table->date('issue_date');
                $table->date('send_date')->nullable();
                $table->integer('category_id');
                $table->integer('status')->default('0');
                $table->string('proposal_module')->default('account');
                $table->integer('is_convert')->default('0');
                $table->integer('converted_invoice_id')->default('0');
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
        Schema::dropIfExists('proposals');
    }
};
