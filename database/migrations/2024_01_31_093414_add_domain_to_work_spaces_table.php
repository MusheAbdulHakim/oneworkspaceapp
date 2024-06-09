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
        Schema::table('work_spaces', function (Blueprint $table) {
            if (!Schema::hasColumn('work_spaces', 'enable_domain')) {
                $table->string('enable_domain')->nullable()->after('name');
                $table->string('domain_type')->nullable()->after('enable_domain');
                $table->string('domain')->nullable()->after('domain_type');
                $table->string('subdomain')->nullable()->after('domain');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_spaces', function (Blueprint $table) {
            //
        });
    }
};
