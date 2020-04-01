<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterActsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_acts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number');
            $table->date('date');
            $table->integer('client_id');
            $table->integer('pay_service');
            $table->integer('company_id');
            $table->integer('file_id')->nullable();
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('register_acts');
    }
}
