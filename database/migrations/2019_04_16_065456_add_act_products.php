<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('act_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('act_id')->references('id')->on('act')->onDelete('CASCADE');
            $table->string('product')->nullable();
            $table->integer('count')->nullable();
            $table->float('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('act_products');
    }
}
