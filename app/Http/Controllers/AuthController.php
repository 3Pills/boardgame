<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRegister;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/game';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Store user into database.
     *
     * @param  array $data
     * @return Response
     */
    public function create($data) {
        do {
            $data['url'] = str_random(8);
        } while (User::where('url', '=', $data['url'])->get()->count());
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        Auth::guard($this->getGuard())->login($user);
        return redirect('/user/'.$user->url);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\CreateUserRegister  $request
     * @return \Illuminate\Http\Response
     */
    public function register(CreateUserRegister $request) {
        return $this->create($request->all());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
    protected function store(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
     */
}
