<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_logs', function (Blueprint $table) {
            $table->id();           
            $table->integer('user_details_id');
            $table->text('resource')->nullable();
            $table->text('filter')->nullable();
            $table->text('index_type')->nullable();
            $table->integer('total')->default(0);
            $table->integer('current_page')->default(0);
            $table->integer('completed')->default(0);           
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
        Schema::dropIfExists('scheduler_logs');
    }
}
