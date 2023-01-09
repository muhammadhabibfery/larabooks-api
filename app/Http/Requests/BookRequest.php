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
        return [
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'title' => ['required', 'min:3', 'max:200', Rule::unique('books', 'title')->ignore($this->book)],
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|numeric|exists:categories,id',
            'image' => 'file|image|max:2500|nullable',
            'description' => 'required|min:10|max:500',
            'weight' => 'required|numeric|min:100',
            'stock' => 'required|integer|min:1',
            'author' => 'required|min:3|max:100',
            'publisher' => 'required|min:3|max:100',
            'price' => 'required|numeric',
            'status' => 'required|in:PUBLISH,DRAFT'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $request = $this->request->all();

        if ($request['price']) {
            $this->merge([
                'price' => integerFormat($request['price'])
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'city_id' => 'city',
        ];
    }
}
