<?php
namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class GroupedRiddlesRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
			'start_at' => 'required|string'
        ];

                
    }
}
