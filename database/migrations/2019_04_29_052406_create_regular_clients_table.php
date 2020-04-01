<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegularClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regular_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->integer('client')->references('id')->on('clients')->onDelete('CASCADE');
            $table->string('service')->references('id')->on('services')->onDelete('CASCADE');
            $table->integer('pay_service')->references('id')->on('pay_services')->onDelete('CASCADE');
            $table->integer('sum');
            $table->boolean('disabled')->nullable();
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
        Schema::dropIfExists('regular_clients');
    }
}
