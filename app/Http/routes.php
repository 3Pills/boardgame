<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function() {

    Route::get('/', function() { return view('welcome'); });

    Route::get('login/', 'AuthController@getLogin');
    Route::post('login/', 'AuthController@login');
    Route::get('logout/', 'AuthController@logout');

    Route::get('register/', 'AuthController@getRegister');
    Route::post('register/', 'AuthController@register');

    Route::group(['prefix' => 'user/'], function() {
        Route::get('/', 'UserController@index');
        Route::get('{url}/', 'UserController@show');
        Route::delete('{url}/', 'UserController@delete');
        Route::get('{url}/settings', 'UserController@edit');
        Route::put('{url}/settings', 'UserController@update');
    });

    Route::group(['prefix' => 'game/'], function() {
        Route::get('/', 'GamesController@index');
        Route::post('/', 'GamesController@create');

        Route::group(['prefix' => '{url}/'], function() {
            Route::get('/', 'GamesController@show');

            Route::post('update/', 'GamesController@update');
            Route::post('roll/', 'GamesController@roll');
            Route::post('chat/', 'GamesController@chat');
            Route::post('getChat/', 'GamesController@getChat');

            Route::get('update/', 'GamesController@update');
            Route::get('roll/', 'GamesController@roll');
            Route::get('chat/', 'GamesController@chat');
            Route::get('getChat/', 'GamesController@getChat');
        });

        Route::post('update/', 'GamesController@update');
        Route::post('roll/', 'GamesController@roll');
        Route::post('chat/', 'GamesController@chat');
        Route::post('getChat/', 'GamesController@getChat');
    });

    //Route::get('/admin/users', 'AdminController@viewall');

    /*
    Route::get('sendMessage', 'ChatController@sendMessage');
    Route::get('isTyping','ChatController@isTyping');
    Route::get('notTyping', 'ChatController@notTyping');
    Route::get('retrieveChatMessages', 'ChatController@retrieveChatMessages');
    Route::get('retrieveTypingStatus', 'ChatController@retrieveTypingStatus');

    Route::post('sendMessage', 'ChatController@sendMessage');
    Route::post('isTyping','ChatController@isTyping');
    Route::post('notTyping', 'ChatController@notTyping');
    Route::post('retrieveChatMessages', 'ChatController@retrieveChatMessages');
    Route::post('retrieveTypingStatus', 'ChatController@retrieveTypingStatus');
    */

    Route::get('/{username}', function($username) {
        return View::make('chats')->with('username',$username);
    });

    //Route::get('/123', ['middleware' => 'auth', function() {
    //	return "123";
    //}]);

    //Route::controllers([
    //	'auth' => 'AuthController',
    //	'password' => 'PasswordController'
    //]);
});
