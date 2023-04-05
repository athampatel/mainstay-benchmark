<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsInAnalaysisExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analaysis_export_requests', function (Blueprint $table) {
            $table->text('request_body')->nullable();
            $table->string('resource')->nullable();
            $table->tinyInteger('is_analysis')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analaysis_export_requests', function (Blueprint $table) {
            //
        });
    }
}
