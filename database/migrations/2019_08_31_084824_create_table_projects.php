<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('company_id');
            $table->string('description')->nullable();
            $table->integer('plan_income')->nullable();
            $table->integer('plan_expense')->nullable();
            $table->integer('fact_income')->nullable();
            $table->integer('fact_expense')->nullable();
            $table->date('date_start')->nullable();;
            $table->date('date_end')->nullable();;
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
        Schema::dropIfExists('projects');
    }
}
