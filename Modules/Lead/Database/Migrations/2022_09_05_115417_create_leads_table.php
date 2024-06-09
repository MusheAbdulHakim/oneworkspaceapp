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
        if(!Schema::hasTable('leads'))
        {
            Schema::create('leads', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('subject');
                $table->integer('user_id');
                $table->integer('pipeline_id');
                $table->integer('stage_id');
                $table->string('sources')->nullable();
                $table->string('products')->nullable();
                $table->text('notes')->nullable();
                $table->string('labels')->nullable();
                $table->integer('order')->default(0);
                $table->string('phone',20)->nullable();
                $table->integer('created_by');
                $table->integer('workspace_id');
                $table->integer('is_active')->default(1);
                $table->integer('is_converted')->default(0);
                $table->date('date')->nullable();
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
        Schema::dropIfExists('leads');
    }
};
