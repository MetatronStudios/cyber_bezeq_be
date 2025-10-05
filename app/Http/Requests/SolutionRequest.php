<?php

namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class SolutionRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_riddle' => 'required|integer',
			'answer' => 'required|string'
        ];
    }
}
