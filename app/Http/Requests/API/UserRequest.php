<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->is('api/register'))
            return [
                'name' => ['required', 'min:3', 'max:100'],
                'username' => ['required', 'alpha_dash', 'unique:users,username'],
                'email' => ['required', 'email', 'unique:users,email'],
                'phone' => ['required', 'digits_between:10,12'],
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ];

        if (request()->is('api/login'))
            return [
                'email' => ['required', 'min:3', 'max:100'],
                'password' => ['required', 'string']
            ];
    }
}
