<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIpActNumberInActTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('act', function (Blueprint $table) {
            $table->integer('number')->nullable()->change();
            $table->integer('ip_act_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('act', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('ip_act_number');
        });
    }
}
