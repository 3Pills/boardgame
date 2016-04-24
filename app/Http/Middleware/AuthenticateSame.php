<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthenticateSame
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        if ( !Auth::check() || ($request->url != Auth::user()->url) ) {
            if (User::where('url', '=', $request->url)->first()) {
                return redirect('/user/'.$request->url);
            }
            else {
                return redirect('/user/');
            }
        }

        return $next($request);
    }
}
