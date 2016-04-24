<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateUserUpdate extends Request {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name' => 'required|between:4,24|alpha_num',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'url' => 'required|alpha_num|unique:users,url,'.Auth::user()->id,
            'profile_picture' => 'image',
            'about' => 'max:1000',
        ];
    }
}
