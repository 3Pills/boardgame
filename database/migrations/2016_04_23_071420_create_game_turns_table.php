<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameTurnsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('game_turns', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');

            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('game_id')->unsigned()->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set NULL');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('set NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('game_turns');
    }
}
