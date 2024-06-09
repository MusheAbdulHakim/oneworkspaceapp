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
        if (!Schema::hasTable('leaves')) {
            Schema::create('leaves', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id');
                $table->integer('user_id');
                $table->integer('leave_type_id');
                $table->date('applied_on');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('total_leave_days');
                $table->longText('leave_reason');
                $table->longText('remark')->nullable();
                $table->string('status');
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
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
        Schema::dropIfExists('leaves');
    }
};
