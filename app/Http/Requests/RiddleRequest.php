<?php

namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class RiddleRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
			'title' => 'required|string'
        ];
    }
}
