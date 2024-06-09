<?php

use App\Models\Coupon;
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
        Schema::table('coupons', function (Blueprint $table) {
            $table->enum('type', array_keys(Coupon::$couponType))->default('percentage')->after('limit');
            $table->integer('minimum_spend')->nullable()->after('type');
            $table->integer('maximum_spend')->nullable()->after('minimum_spend');
            $table->integer('limit_per_user')->nullable()->after('maximum_spend');
            $table->date('expiry_date')->nullable()->after('limit_per_user');
            $table->string('included_module')->nullable()->after('expiry_date');
            $table->string('excluded_module')->nullable()->after('included_module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
};
