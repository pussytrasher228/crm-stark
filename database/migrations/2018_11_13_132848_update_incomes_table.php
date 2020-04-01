<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->date('plan_date')->nullable()->change();
            $table->date('income_date')->nullable()->change();
            $table->integer('client')->references('id')->on('clients')->onDelete('CASCADE')->change();
            $table->integer('user')->references('id')->on('users')->onDelete('CASCADE')->change();
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
            $table->date('plan_date')->change();
            $table->date('income_date')->change();
            $table->string('client')->nullable()->change();
            $table->string('user')->nullable()->change();
        });
    }
}
