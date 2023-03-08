<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('itemcode')->nullable();
            $table->text('itemcodedesc')->nullable();
            $table->integer('product_line_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->text('aliasitemno')->nullable();;
            $table->text('aliasitemdesc')->nullable();;
            $table->integer('quantityonhand')->default(0);
            $table->double('unitprice')->default(0);
            $table->double('vmiprice')->default(0);
            $table->text('productlinedesc')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('products');
    }
}
