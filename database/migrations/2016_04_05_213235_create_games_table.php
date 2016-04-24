<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->integer('map')->unsigned();

            $table->integer('player_id1')->unsigned()->nullable();
            $table->integer('player_id2')->unsigned()->nullable();
            $table->integer('player_id3')->unsigned()->nullable();
            $table->integer('player_id4')->unsigned()->nullable();

            $table->foreign('player_id1')->references('id')->on('players')->onDelete('set NULL');
            $table->foreign('player_id2')->references('id')->on('players')->onDelete('set NULL');
            $table->foreign('player_id3')->references('id')->on('players')->onDelete('set NULL');
            $table->foreign('player_id4')->references('id')->on('players')->onDelete('set NULL');

            $table->boolean('private')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('games');
    }
}
