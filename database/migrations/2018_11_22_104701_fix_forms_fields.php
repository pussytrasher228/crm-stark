<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixFormsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('checking_account')->nullable()->change();
            $table->string('ks')->nullable()->change();
            $table->string('inn')->nullable()->change();
            $table->string('kpp')->nullable()->change();
            $table->string('bik')->nullable()->change();
            $table->string('bank_name')->nullable()->change();
            $table->string('ur_address')->nullable()->change();
            $table->string('fact_address')->nullable()->change();
            $table->string('mail_address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('checking_account')->change();
            $table->string('ks')->change();
            $table->string('inn')->change();
            $table->string('kpp')->change();
            $table->string('bik')->change();
            $table->string('bank_name')->change();
            $table->string('ur_address')->change();
            $table->string('fact_address')->change();
            $table->string('mail_address')->change();
        });
    }
}
