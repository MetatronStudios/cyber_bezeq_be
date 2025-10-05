<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * @codeCoverageIgnore
 */
class ParentController extends Controller
{
    protected $service;

    protected function respondWithError($error, $statusCodeError=422)
    {
        return response()->json(['errors' => $error])->setStatusCode($statusCodeError);
    }

    protected function respondWithData($data=[], $statusCode=200)
    {
        return response()->json($data)->setStatusCode($statusCode);
    }
}