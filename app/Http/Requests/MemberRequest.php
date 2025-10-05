<?php
namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class MemberRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_user' => 'required|integer'
        ];
    }
}
