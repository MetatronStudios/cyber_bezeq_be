<?php
namespace App\Http\Requests;

/**
 * @codeCoverageIgnore
 */
class ImportGroupsRequest extends ParentRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          
        ];

                
    }
}
