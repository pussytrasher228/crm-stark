<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->string('client')->nullable();
            $table->string('service')->nullable();
            $table->string('pay_service')->nullable();
            $table->integer('external_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('client');
            $table->dropColumn('service');
            $table->dropColumn('pay_service');
            $table->dropColumn('external_id');
        });
    }
}
