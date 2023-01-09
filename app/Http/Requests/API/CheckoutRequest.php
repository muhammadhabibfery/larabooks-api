<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->is('api/checkout'))
            return [
                'name' => ['required', 'min:3', 'max:100'],
                'address' => ['required'],
                'phone' => ['required', 'digits_between:10,12'],
                'provincy_id' => ['required', 'exists:provincies,id'],
                'city_id' => ['required', 'exists:cities,id'],
            ];

        if ($this->is('api/checkout/process'))
            return [
                'courier' => ['required', 'in:jne,pos,tiki'],
                'cart' => ['required', 'array'],
                'cart.*.cityId' => ['required', 'exists:books,city_id'],
                'cart.*.price' => ['required', 'exists:books,price'],
                'cart.*.weight' => ['required', 'exists:books,weight'],
            ];

        if ($this->is('api/checkout/submit'))
            return [
                'data' => ['required', 'array'],
                'data.*.cart' => ['required', 'array'],
                'data.*.courier' => ['required', 'in:jne,pos,tiki'],
                'data.*.service' => ['required', 'in:REG,OKE,YES'],
                'data.*.cart.*.price' => ['required', 'exists:books,price'],
                'data.*.cart.*.weight' => ['required', 'exists:books,weight'],
                'data.*.cart.*.cityId' => ['required', 'exists:books,city_id'],
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

        if ($this->is('api/checkout') && $this->has('provinceId') && $this->has('cityId')) {
            $this->merge([
                'provincy_id' => $request['provinceId'],
                'city_id' => $request['cityId']
            ]);
        }
    }
}
