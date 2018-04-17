<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CollectionRequest extends FormRequest
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
            case 'questionCreate': {
                return [
                    'collection_id' => 'required|integer',
                    'content' => 'required',
                    'answers' => 'required|array',
                    'point_ladder' => 'integer|min:0'
                ];
            }
            case 'questionAttach': {
                return [
                    'collection_id' => 'required|integer',
                    'question_ids' => 'required|array',
                    'attach' => 'boolean',
                ];
            }
            case 'publish': {
                return [
                    'ids' => 'required|array',
                    'publish' => 'boolean',
                ];
            }
            default: return [
                'name' => 'required',
                'time' => 'required|integer',
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
