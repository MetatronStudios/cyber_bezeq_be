<?php

namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class FactRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
			'is_correct' => 'required|integer'
        ];
    }
}
