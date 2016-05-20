<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use Lang;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRegister;
use App\Http\Requests\CreateUserLogin;
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
     * Where to redirect users who fail to login.
     *
     * @var string
     */
    protected $loginPath = '/login';

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
     * Handle a login request for the application.
     *
     * @param  \Illuminate\Http\CreateUserLogin  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(CreateUserLogin $request) {
       
        return $this->login($request);
    }


    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(CreateUserLogin $request)
    {
        return redirect($this->loginPath)
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }
}
