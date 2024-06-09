<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions', function (Blueprint $table) {
            if (!Schema::hasColumn('commissions', 'start_date')) {
                $table->date('start_date')->after('amount')->nullable();
            }

            if (!Schema::hasColumn('commissions', 'end_date')) {
                $table->date('end_date')->after('start_date')->nullable();
            }

            if (!Schema::hasColumn('commissions', 'status')) {
                $table->string('status')->after('end_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions', function (Blueprint $table) {

        });
    }
};
