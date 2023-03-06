<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->text('salesorderno');
            $table->integer('user_details_id');
            $table->date('orderdate');
            $table->text('shiptoname')->nullable();
            $table->text('shiptoaddress1')->nullable();
            $table->text('shiptoaddress2')->nullable();
            $table->text('shiptoaddress3')->nullable();
            $table->text('shiptocity')->nullable();
            $table->text('shiptostate')->nullable();
            $table->text('shiptozipcode')->nullable();
            $table->text('shipvia')->nullable();
            $table->double('taxablesalesamt')->default(0);
            $table->double('nontaxablesalesamt')->default(0);
            $table->double('freightamt')->default(0);
            $table->double('salestaxamt')->default(0);
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
        Schema::dropIfExists('sale_orders');
    }
}
