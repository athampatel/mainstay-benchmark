<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeOrderRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_order_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('order_no');
            $table->tinyInteger('request_status')->default(0);
            $table->string('status_detail')->nullable();
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('sync')->default(0);
            $table->string('ordered_date');
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
        Schema::dropIfExists('change_order_requests');
    }
}
