<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if (request()->routeIs('users.store'))
            return [
                'city_id' => ['required', 'integer', 'exists:cities,id'],
                'name' => 'required|min:3|max:100',
                'email' => "required|email|unique:users,email",
                'nik' => "required|numeric|digits:13|unique:users,nik",
                'phone' => 'required|digits_between:10,12',
                'address' => 'required|min:10|max:100',
            ];

        if (request()->routeIs('users.update'))
            return [
                'role' => 'required|in:ADMIN,STAFF',
                'status' => 'required|in:ACTIVE,INACTIVE'
            ];

        if (request()->routeIs('profiles.update'))
            return [
                'name' => 'required|min:3|max:100',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user())],
                'phone' => 'required|digits_between:10,12',
                'address' => 'required|min:10|max:100',
                'image' => 'nullable|image|max:2500'
            ];

        if (request()->routeIs('profiles.password.update'))
            return [
                'current_password' => ['required', 'string', 'password'],
                'new_password' => ['required', 'different:current_password', 'min:5', 'confirmed'],
            ];
    }
}
