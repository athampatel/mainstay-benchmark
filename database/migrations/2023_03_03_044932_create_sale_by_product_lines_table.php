<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleByProductLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_by_product_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('user_details_id');
            $table->text('ProductLine')->nullable();
            $table->text('year')->nullable();
            $table->text('month')->nullable();
            $table->text('value')->nullable();
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
        Schema::dropIfExists('sale_by_product_lines');
    }
}
