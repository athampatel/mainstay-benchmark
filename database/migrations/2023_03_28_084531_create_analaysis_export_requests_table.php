<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalaysisExportRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analaysis_export_requests', function (Blueprint $table) {
            $table->id();
            $table->string('customer_no');
            $table->string('user_detail_id');
            $table->string('ardivisiono');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('year');
            $table->string('unique_id');
            $table->tinyInteger('type')->comment('1 - csv, 2 - pdf');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('analaysis_export_requests');
    }
}
