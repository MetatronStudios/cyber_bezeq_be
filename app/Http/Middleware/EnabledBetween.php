<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;

class EnabledBetween
{
    public function handle($request, Closure $next)
    {
        include app_path() . '/Utils/FinalData.php';
        $now    = time();
        $start = $ENABLED_START ? strtotime($ENABLED_START) : $now-1;
        $end   = $ENABLED_END ? strtotime($ENABLED_END) : $now+1;

        // if now is between start and end, then allow the request
        if ( ( $now > $start ) && ( $now < $end ) ) {
            return $next($request);
        }
        else {
            return response()->json(['errors' => 'not allowed'])->setStatusCode(423);
        }


    }
}
