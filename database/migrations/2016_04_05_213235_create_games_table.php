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
            $table->string('url')->unique();
            $table->integer('map')->unsigned();

            $table->integer('player_id1')->unsigned()->nullable()->index();
            $table->integer('player_id2')->unsigned()->nullable()->index();
            $table->integer('player_id3')->unsigned()->nullable()->index();
            $table->integer('player_id4')->unsigned()->nullable()->index();

            $table->boolean('private')->default(false)->index();
            $table->timestamp('created_at');
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
