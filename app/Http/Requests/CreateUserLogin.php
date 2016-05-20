<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserLogin extends Request {
    /**
     * The URI to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirect = '/login';

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
            'email' => 'required|email',
            'password' => 'required|between:8,30',
        ];
    }
}
