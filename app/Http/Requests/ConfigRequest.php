<?php

namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class ConfigRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
			'text' => 'required|string'
        ];
    }
}
