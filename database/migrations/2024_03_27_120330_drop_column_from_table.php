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

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'day_type','start_date','end_date')) {
                $table->dropColumn('day_type');
                $table->dropColumn('start_date');
                $table->dropColumn('end_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
