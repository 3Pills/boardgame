<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserRegister extends Request {
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
            'password' => 'required|between:8,32|regex:/^[a-zA-Z0-9`~!@#$%^&*-_+=]*$/|confirmed',
            'email' => 'required|email|unique:users,email',
        ];
    }

    /**
     * Apply custom error messages to appropriate errors.
     *
     * @return array
     */
    public function messages() {
        return [
            'password.regex' => 'Password cannot contain special characters, except for the following: `~!@#$%^&*_-+='
        ];
    }
}