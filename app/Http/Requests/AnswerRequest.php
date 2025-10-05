<?php

namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class AnswerRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
			'id_riddle' => 'required|integer',
            'text' => 'required|string'
        ];
    }
}
