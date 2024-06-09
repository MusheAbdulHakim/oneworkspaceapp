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
	    if (!Schema::hasTable('landingpage_pixels')) {
		Schema::create('landingpage_pixels', function (Blueprint $table) {
		    $table->id();
		    $table->string('platform')->nullable();
		    $table->string('pixel_id')->nullable();
		    $table->timestamps();
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
        Schema::dropIfExists('pixels');
    }
};
