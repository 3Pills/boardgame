<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserLogin extends Request {
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
        $user = User::find($this->users);
        
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|between:8,30',
        ];
    }

    /**
     * Apply custom error messages to appropriate errors.
     *
     * @return array
    public function messages() {
        return [
            'password.regex' => 'Password cannot contain special characters, except for the following [!@#$%&*_ ]',
        ];
    }
     */
}
