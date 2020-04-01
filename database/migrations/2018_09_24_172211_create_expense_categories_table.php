<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->integer('parent_id')->references('id')->on('expense_categories')->onDelete('CASCADE')->nullable();
            $table->timestamps();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->integer('category')->references('id')->on('expense_categories')->onDelete('CASCADE')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_categories');

        Schema::table('expenses', function (Blueprint $table) {
            $table->string('category')->change();
        });
    }
}
