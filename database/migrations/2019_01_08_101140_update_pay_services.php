<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePayServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pay_services', function (Blueprint $table) {
            $table->string('checking_account')->nullable();
            $table->string('ks')->nullable();
            $table->string('inn')->nullable();
            $table->string('kpp')->nullable();
            $table->string('bik')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ur_address')->nullable();
            $table->string('fact_address')->nullable();
            $table->string('mail_address')->nullable();
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
            $table->dropColumn('checking_account');
            $table->dropColumn('ks');
            $table->dropColumn('inn');
            $table->dropColumn('kpp');
            $table->dropColumn('bik');
            $table->dropColumn('bank_name');
            $table->dropColumn('ur_address');
            $table->dropColumn('fact_address');
            $table->dropColumn('mail_address');
        });
    }
}
