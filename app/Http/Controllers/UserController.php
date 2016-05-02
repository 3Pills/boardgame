<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateUserUpdate;
use App\User;

use Carbon\Carbon;
use DB;
use Hash;
use Auth;
use Image;

class UserController extends Controller {
    /**
     * Create a new user controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth.owner', ['except' => ['index', 'show', 'update']]);
    }

    /**
     * Access Register Page for Users.
     *
     * @return Response
     */
    public function index() {
        //return redirect()->route('/users', [$user->name]);
        return view('users.index');
    }

    /**
     * View User information.
     *
     * @return Response
     */
    public function show($url) {
        $user = User::where('url', '=', $url)->first();
        if ($user === null) {
            return view('users.404');
        }
        return view('users.profile', compact('user'));
    }

    /**
     * Access User Info page for Users.
     *
     * @return Response
     */
    public function edit($url) {
        $user = User::where('url', '=', $url)->firstOrFail();
        return view('users.settings', compact('user'));
    }

    /**
     * Access User Info page for Users.
     *
     * @param CreateUserUpdate $request
     * @return Response
     */
    public function update(CreateUserUpdate $request) {
        $user = $request->user();
        $imgDir = public_path().'/assets/images/avatars/';
        $prevUrl = $user->url;
        $user->update($request->all());
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            if (file_exists($imgDir.$prevUrl.'.jpg')) {
                unlink($imgDir.$prevUrl.'.jpg');
            }
            $imgName = $user->url.'.'.$request->file('profile_picture')->getClientOriginalExtension();

            $request->file('profile_picture')->move($imgDir, $imgName);
            $img = Image::make($imgDir.$imgName)->fit(192, 192);

            unlink($imgDir.$imgName);

            $img->save($imgDir.$user->url.'.jpg');
        }
        return redirect('/user/'.$user->url.'/settings/');
    }

    /**
     * View all users.
     *
     * @return Response
     */
    public function viewall() {
        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }
}
