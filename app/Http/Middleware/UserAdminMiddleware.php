<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Auth;

/**
 * @codeCoverageIgnore
 */
class UserAdminMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        $this->checkForToken($request);
        if ( Auth::user()->type != 'A') {
            return response()->json(['errors' => 'not allowed'])->setStatusCode(401);
        }
        return $next($request);
    }
}
