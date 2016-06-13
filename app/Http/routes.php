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
    Route::post('login/', 'AuthController@postLogin');
    Route::get('logout/', 'AuthController@logout');

    Route::get('register/', 'AuthController@getRegister');
    Route::post('register/', 'AuthController@register');

    Route::get('verify/{code}', 'EmailController@verify');
    Route::get('send_verify/', 'EmailController@postEmail');
    
    Route::get('password_reset/{code}', 'PasswordController@reset');

    Route::group(['prefix' => 'user/', 'as' => 'user::'], function() {
        Route::get('/', 'UserController@index');
        Route::get('{url}/', 'UserController@show');
        Route::delete('{url}/', 'UserController@delete');
        Route::get('{url}/settings', 'UserController@edit');
        Route::post('{url}/settings', 'UserController@update');
    });

    Route::group(['prefix' => 'game/', 'middleware' => 'auth', 'as' => 'game::'], function() {
        Route::get('/', 'GamesController@index');
        Route::post('/', 'GamesController@create');

        Route::group(['prefix' => '{url}/'], function() {
            Route::get('/', 'GamesController@show');

            Route::get('update/', 'GamesController@getUpdate');
            Route::post('update/', 'GamesController@postUpdate');

            Route::get('pList/', 'GamesController@getPlayerList');
            Route::get('pData/', 'GamesController@getPlayerData');

            Route::post('join/', 'GamesController@postJoin');
            Route::post('ready/', 'GamesController@postReady');
            Route::post('loaded/', 'GamesController@postLoaded');

            Route::post('ping/', 'GamesController@postPing');

            Route::get('chat/', 'GamesController@getChat');
            Route::post('chat/', 'GamesController@postChat');

            Route::get('roll/', 'GamesController@getRoll');
            Route::post('roll/', 'GamesController@postRoll');
        });
    });

    Route::group(['prefix' => 'admin/', 'middleware' => 'auth', 'as' => 'admin::'], function() {
        Route::get('/', 'AdminController@index');
    });

    Route::get('/bridge', function() {
        $pusher = App::make('pusher');

        $pusher->trigger( 'test-channel',
                          'test-event', 
                          array('text' => 'Preparing the Pusher Laracon.eu workshop!'));

        return view('welcome');
    });

    /*
    Route::get('/admin/users', 'AdminController@viewall');

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

    Route::get('/{username}', function($username) {
        return View::make('chats')->with('username',$username);
    });

    Route::get('/123', ['middleware' => 'auth', function() {
    	return "123";
    }]);

    Route::controllers([
    	'auth' => 'AuthController',
    	'password' => 'PasswordController'
    ]);
    */
});
