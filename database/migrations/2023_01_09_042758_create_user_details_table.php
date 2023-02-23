<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('customerno');
            $table->string('customername');
            $table->string('addressline1')->nullable();
            $table->string('addressline2')->nullable();
            $table->string('addressline3')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('ardivisionno');
            $table->text('vmi_companycode')->nullable();
            $table->integer('is_active')->default(0);
            $table->timestamp('updated_by')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('customername')->nullable()->change();
            $table->string('addressline1')->nullable()->change();
            $table->string('addressline2')->nullable()->change();
            $table->string('addressline3')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('zipcode')->nullable()->change();
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
        Schema::dropIfExists('user_details');
    }
}
