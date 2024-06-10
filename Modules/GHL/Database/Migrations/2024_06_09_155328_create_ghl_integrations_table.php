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
        Schema::create('ghl_integrations', function (Blueprint $table) {
            $table->id();
            $table->longText('access_token')->nullable();
            $table->longText('scopes')->nullable();
            $table->string('access_expires_in')->nullable();
            $table->string('token_type')->nullable();
            $table->string('userType')->nullable();
            $table->string('companyId')->nullable();
            $table->string('locationId')->nullable();
            $table->string('userId')->nullable();
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
        Schema::dropIfExists('ghl_integrations');
    }
};
