<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadyAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ready_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('checking_account');
            $table->string('pay_services');
            $table->string('ks');
            $table->string('inn');
            $table->string('kpp');
            $table->string('bik');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('services');
            $table->string('sum');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('ready_account');
    }
}
