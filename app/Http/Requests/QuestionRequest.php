<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuestionRequest extends FormRequest
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

            case 'update': return [
                'content' => 'required',
            ];

            case 'answerAttach': return [
                'question_id' => 'required|integer',
                'answer_ids'  => 'required|array',
                'attach'      => 'boolean',
            ];

            default: return $rules = [
                'content' => 'required',
                'answers' => 'required|array',
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
