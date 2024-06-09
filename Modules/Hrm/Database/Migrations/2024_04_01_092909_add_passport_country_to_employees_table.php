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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'passport_country')) {
                $table->string('passport_country')->after('account_type')->nullable();
            }

            if (!Schema::hasColumn('employees', 'passport')) {
                $table->string('passport')->after('passport_country')->nullable();
            }

            if (!Schema::hasColumn('employees', 'location_type')) {
                $table->string('location_type')->after('passport')->nullable();
            }

            if (!Schema::hasColumn('employees', 'country')) {
                $table->string('country')->after('location_type')->nullable();
            }
            
            if (!Schema::hasColumn('employees', 'state')) {
                $table->string('state')->after('country')->nullable();
            }

            if (!Schema::hasColumn('employees', 'city')) {
                $table->string('city')->after('state')->nullable();
            }

            if (!Schema::hasColumn('employees', 'zipcode')) {
                $table->string('zipcode')->after('city')->nullable();
            }

            if (!Schema::hasColumn('employees', 'hours_per_day')) {
                $table->float('hours_per_day', 30, 2)->after('zipcode')->nullable();
            }

            if (!Schema::hasColumn('employees', 'annual_salary')) {
                $table->integer('annual_salary')->after('hours_per_day')->nullable();
            }

            if (!Schema::hasColumn('employees', 'days_per_week')) {
                $table->integer('days_per_week')->after('annual_salary')->nullable();
            }

            if (!Schema::hasColumn('employees', 'fixed_salary')) {
                $table->integer('fixed_salary')->after('days_per_week')->nullable();
            }
            
            if (!Schema::hasColumn('employees', 'hours_per_month')) {
                $table->float('hours_per_month', 30, 2)->after('fixed_salary')->nullable();
            }

            if (!Schema::hasColumn('employees', 'rate_per_day')) {
                $table->integer('rate_per_day')->after('hours_per_month')->nullable();
            }

            if (!Schema::hasColumn('employees', 'days_per_month')) {
                $table->integer('days_per_month')->after('rate_per_day')->nullable();
            }

            if (!Schema::hasColumn('employees', 'rate_per_hour')) {
                $table->integer('rate_per_hour')->after('days_per_month')->nullable();
            }

            if (!Schema::hasColumn('employees', 'payment_requires_work_advice')) {
                $table->string('payment_requires_work_advice')->after('rate_per_hour')->default('off');
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
        Schema::table('employees', function (Blueprint $table) {

        });
    }
};
