<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Auth;

/**
 * @codeCoverageIgnore
 */
class UserFinalistMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        $this->checkForToken($request);
        if ( Auth::user()->type != 'F') {
            return response()->json(['errors' => 'not allowed'])->setStatusCode(406);
        }
        return $next($request);
    }
}
