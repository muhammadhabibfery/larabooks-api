<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
            'title' => "required|min:3|max:200|unique:books,title,except,{$this->id}",
            'image' => 'file|image|max:2500|nullable',
            'description' => 'required|min:10|max:500',
            'stock' => 'required|integer|digits_between:0,5s',
            'author' => 'required|min:3|max:100',
            'publisher' => 'required|min:3|max:100',
            'price' => 'required|numeric',
            'action' => ['required', Rule::in(['PUBLISH', 'DRAFT'])],
        ];

        if (request()->isMethod('patch') || request()->isMethod('put')) {
            $rules['title'] = ['required', Rule::unique('books', 'title')->ignore($this->book)];
        }

        return $rules;
    }
}
