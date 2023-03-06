<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicedOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      

        Schema::create('invoiced_orders', function (Blueprint $table) {
            $table->id();
            $table->text('invoiceno');
            $table->integer('sale_orders_id');
            $table->text('headerseqno')->nullable();
            $table->date('invoicedate');            
            $table->text('customerpono')->nullable();
            $table->text('termscode')->nullable();
            $table->text('ardivisionno')->nullable();
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
        Schema::dropIfExists('invoiced_orders');
    }
}
