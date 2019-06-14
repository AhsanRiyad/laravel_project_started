<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bazar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('taka', 8, 2);
            $table->date('bazar_date');
            $table->date('adding_date');
            $table->string('type');
            $table->bigInteger('user');
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
        Schema::dropIfExists('bazar');
    }
}
