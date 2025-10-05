<?php

namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class ReplyRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'replies' => 'required|string'
        ];
    }
}
