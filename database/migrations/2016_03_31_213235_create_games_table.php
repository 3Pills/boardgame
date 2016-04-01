<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user1_id')->unsigned();
            $table->integer('user2_id')->nullable()->unsigned();
            $table->integer('user3_id')->nullable()->unsigned();
            $table->integer('user4_id')->nullable()->unsigned();
            $table->boolean('private');
            $table->timestamps();

            $table->foreign('user1_id')->references('id')->on('users');
            $table->foreign('user2_id')->references('id')->on('users');
            $table->foreign('user3_id')->references('id')->on('users');
            $table->foreign('user4_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('games');
    }
}
