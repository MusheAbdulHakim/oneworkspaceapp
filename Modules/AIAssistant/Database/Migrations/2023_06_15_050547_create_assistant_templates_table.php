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
        if (!Schema::hasTable('assistant_templates'))
        {
            Schema::create('assistant_templates', function (Blueprint $table) {
                $table->id();
                $table->string('template_name');
                $table->string('template_module');
                $table->string('module');
                $table->text('prompt');
                $table->text('field_json');
                $table->integer('is_tone')->default(0);
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
        Schema::dropIfExists('assistant_templates');
    }
};
