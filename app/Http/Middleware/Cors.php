<?php

namespace App\Http\Middleware;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use Closure;

/**
 * @codeCoverageIgnore
 */
class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return response()->json(['errors' => 'This cors middleware is not supposed to be called'])->setStatusCode(500);
    //    return $next($request)
    //       ->header('Access-Control-Allow-Origin', '*')
    //       ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

    }
}
