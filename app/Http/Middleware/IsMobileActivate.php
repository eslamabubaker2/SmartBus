<?php

namespace App\Http\Middleware;

use Closure;

class IsMobileActivate
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

        if (auth('api')->user()->state != 1) return apiError('smartbus.your_account_not_active');
        return $next($request);
    }

}
