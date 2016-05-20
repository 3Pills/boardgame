<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->nullable();
            $table->string('name', 24);
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('verified')->default(false);

            $table->string('url', 32)->unique();
            $table->string('about')->nullable();
            $table->integer('fave_char')->unsigned()->nullable();

            $table->integer('level')->unsigned()->default(1);
            $table->integer('exp')->unsigned()->default(0);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('users');
    }
}
