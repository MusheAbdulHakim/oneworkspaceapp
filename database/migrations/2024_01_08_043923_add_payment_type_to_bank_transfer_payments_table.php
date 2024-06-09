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

        Schema::table('bank_transfer_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_transfer_payments', 'payment_type')) {
                $table->string('payment_type')->after('type')->default('Bank Transfer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transfer_payments', function (Blueprint $table) {
            //
        });
    }
};
