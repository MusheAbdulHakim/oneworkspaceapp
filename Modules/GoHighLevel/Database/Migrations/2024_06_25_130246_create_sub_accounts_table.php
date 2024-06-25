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
        Schema::create('sub_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gohighlevel_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('user_id')->nullable();
            $table->integer('workspace')->nullable();
            $table->text('snapshot')->nullable();
            $table->text('social')->nullable();
            $table->jsonb('permissions')->nullable();
            $table->jsonb('scopes')->nullable();
            $table->text('ghl_user_id')->nullable();
            $table->text('locationId')->nullable();
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
        Schema::dropIfExists('sub_accounts');
    }
};
