<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;

use App\Http\Requests;
use App\EmailVerify;
use App\User;

use Mail;
use Redirect;

class EmailController extends Controller
{
    use ResetsPasswords;

    /**
     * Handle a verification request for a user.
     *
     * @param  \Illuminate\Http\CreateUserLogin  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, $code) {
        if (!$code) {
            return "no matching code found";
        }
        $email = EmailVerify::where('token', '=', $code)->first();
        if (!$email) {
            return "no matching code found";
        }

        $user = User::where('email','=',$email->email)->first();

        if (!$user) {
            return "no user found";
        }
        $user->role = 1;
        $user->save();

        $email->delete();

        return Redirect::home();
    }

    /**
     * Handle a verification request for a user.
     *
     * @param  \Illuminate\Http\CreateUserLogin  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request) {
        $code = str_random(20);
        $verify = EmailVerify::create(['email' => $request->user()->email, 'token' => $code]);
        $user = $request->user();

        Mail::send('email.verify', ['code' => $code], function ($message) use ($user) {
            $message->from('admin@3pills.memers.club', 'Admin');
            $message->sender('admin@3pills.memers.club', 'Admin');
        
            $message->to($user->email, $user->name);
        
            $message->cc($user->email, $user->name);
            $message->bcc($user->email, $user->name);
        
            $message->replyTo($user->email, $user->name);
        
            $message->subject('Please verify your email');
        
            $message->priority(3);
        });

        return back();
    }

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
