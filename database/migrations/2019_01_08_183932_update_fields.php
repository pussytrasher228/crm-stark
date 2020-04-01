<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pay_services', function (Blueprint $table) {
            $table->string('bank_account')->nullable();
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->float('sum')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pay_services', function (Blueprint $table) {
            $table->dropColumn('bank_account');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->integer('sum')->change();
        });
    }
}
