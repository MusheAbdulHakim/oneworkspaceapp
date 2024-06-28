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
        Schema::create('subaccount_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_account_id')->nullable()->constrained()->onDelete('cascade');
            $table->longText('access_token')->nullable();
            $table->string('token_type')->nullable();
            $table->string('expires_in')->nullable();
            $table->longText('refresh_token');
            $table->longText('scope')->nullable();
            $table->string('userType')->nullable()->default('Location');
            $table->string('companyId')->nullable();
            $table->string('locationId')->nullable();
            $table->string('userId')->nullable();
            $table->string('traceId')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('workspace')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subaccount_tokens');
    }
};
