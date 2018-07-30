<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class CheckoutRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'requestAddPoint': {
                return [];
            }
            default: return [];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
