<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
        $rules = [];

        if (request()->isMethod('put') || request()->isMethod('patch')) {
            $rules['status'] = ['required', Rule::in(['SUBMIT', 'PROCESS', 'FINISH', 'CANCEL'])];
        }

        return $rules;
    }
}
