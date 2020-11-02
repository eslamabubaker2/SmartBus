<?php

namespace App\Http\Middleware;

use Closure;

class IsSchool
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

        if (auth('api')->user()->role != 1) return apiError('smartbus.No_Permission_Acount');
        return $next($request);

    }
}
