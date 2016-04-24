<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('chats', function(Blueprint $table) {
			$table->increments('id');

            $table->integer('user_id1')->unsigned()->nullable();
            $table->integer('user_id2')->unsigned()->nullable();
            $table->foreign('user_id1')->references('id')->on('users')->onDelete('set NULL');
            $table->foreign('user_id2')->references('id')->on('users')->onDelete('set NULL');

            $table->boolean('user1_is_typing')->default(false);
            $table->boolean('user2_is_typing')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('chats');
	}

}
