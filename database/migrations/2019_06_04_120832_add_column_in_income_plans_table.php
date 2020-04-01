<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInIncomePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_plans', function (Blueprint $table) {
            $table->string('company');
            $table->string('mounth_name');
            $table->string('month')->nullable()->change();
            $table->string('year')->nullable()->change();
            $table->integer('plan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_plans', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('mounth_name');
            $table->dropColumn('month');
            $table->dropColumn('year');
            $table->dropColumn('plan');
        });
    }
}
