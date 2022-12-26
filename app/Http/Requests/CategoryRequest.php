<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        $rules = [
            'name' => "required|min:3|max:50|unique:categories,name,except,{$this->id}",
            'image' => 'file|image|max:2500',
        ];

        if (request()->isMethod('patch') || request()->isMethod('put')) {
            $rules['name'] = ['required', Rule::unique('categories', 'name')->ignore($this->category)];
        }

        return $rules;
    }
}
