<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\AllowedIpRepository;
use Config;

class AllowedIpMiddleware
{
    protected $repository;
    public function __construct()
    {
        $this->repository = new AllowedIpRepository();
    }

    public function handle($request, Closure $next)
    {
        if (!env('ENABLE_IP_FILTER')) {
            return $next($request);
        }
        else {
            if ($this->repository->getByIp($request->ip())) {
                return $next($request);
            }
            return response()->json(['errors' => 'not allowed'])->setStatusCode(401);
        }
    }
}
