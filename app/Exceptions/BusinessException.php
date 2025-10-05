<?php

namespace App\Exceptions;

use Exception;

/**
 * @codeCoverageIgnore
 */
class BusinessException extends Exception
{
    public function render($request)
    {
        return response()->json(['errors' => $this->getMessage()])->setStatusCode(422);
    }
}
