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
        Schema::table('chart_of_accounts', function (Blueprint $table) {

        });

        if (!Schema::hasColumn('chart_of_accounts', 'parent'))
        {
            Schema::table('chart_of_accounts', function (Blueprint $table) {
                $table->integer('parent')->default(0)->after('sub_type');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {

        });
    }
};
