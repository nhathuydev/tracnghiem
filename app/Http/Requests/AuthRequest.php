<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest
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
        switch ($this->route()->getActionMethod()) {
            case 'login': {
                if ($this->request->has('access_token')) {
                    return [
                        'access_token' => 'required',
                        'driver' => 'required|in:facebook,github,google',
                    ];
                }
                return [
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                ];
            }
            case 'register': {
                return [
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:6',
                    'name' => 'required',
                ];
            }
            default: return [];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
