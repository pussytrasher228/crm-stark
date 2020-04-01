<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('checking_account');
            $table->string('ks');
            $table->string('inn');
            $table->string('kpp');
            $table->string('bik');
            $table->string('bank_name');
            $table->string('ur_address');
            $table->string('fact_address');
            $table->string('mail_address');
            $table->integer('company_id')->references('id')->on('companies')->onDelete('CASCADE');
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
        Schema::dropIfExists('clients');
    }
}
