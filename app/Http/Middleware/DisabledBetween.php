<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;

class DisabledBetween
{
    public function handle($request, Closure $next)
    {
        include app_path() . '/Utils/FinalData.php';
        $now    = time();
        $start  = $DISABLED_START ? strtotime($DISABLED_START) : $now-1;
        $end    = $DISABLED_END ? strtotime($DISABLED_END) : $now+1;

        // if now is not between start and end, then allow the request
        if ( ( $now < $start ) || ( $now > $end ) ) {
            return $next($request);
        }
        else {
            return response()->json(['errors' => 'not allowed'])->setStatusCode(423);
        }
    }
}
