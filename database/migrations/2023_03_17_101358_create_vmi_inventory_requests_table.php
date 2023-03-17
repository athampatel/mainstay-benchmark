<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVmiInventoryRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vmi_inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->string('company_code');
            $table->string('item_code');
            $table->integer('user_detail_id');
            $table->integer('old_qty_hand')->default(0);
            $table->integer('new_qty_hand')->default(0);
            $table->integer('change_user');
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
        Schema::dropIfExists('vmi_inventory_requests');
    }
}
