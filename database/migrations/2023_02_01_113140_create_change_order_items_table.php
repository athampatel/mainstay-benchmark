<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_table_id');
            $table->string('item_code');
            $table->integer('existing_quantity');
            $table->integer('modified_quantity');
            $table->integer('order_item_price');
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
        Schema::dropIfExists('change_order_items');
    }
}
