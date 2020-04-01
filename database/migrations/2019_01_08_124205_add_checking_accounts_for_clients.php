<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckingAccountsForClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_checking_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('checking_account')->nullable();
            $table->string('ks')->nullable();
            $table->string('inn')->nullable();
            $table->string('kpp')->nullable();
            $table->string('bik')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ur_address')->nullable();
            $table->string('fact_address')->nullable();
            $table->string('mail_address')->nullable();
            $table->integer('client_id')->references('id')->on('clients')->onDelete('CASCADE');
            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_checking_accounts');

        Schema::table('clients', function (Blueprint $table) {
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
}
