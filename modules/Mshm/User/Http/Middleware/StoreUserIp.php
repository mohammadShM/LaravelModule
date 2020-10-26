<?php

namespace Mshm\User\Http\Middleware;

use Closure;

class StoreUserIp
{
    public function handle($request, Closure $next)
    {
        // for save ip in users table
        if (auth()->check() && auth()->user()->ip != $request->ip()) {
            auth()->user()->ip = $request->ip();
            /** @noinspection PhpUndefinedMethodInspection */
            auth()->user()->save();
        }
        return $next($request);
    }
}
