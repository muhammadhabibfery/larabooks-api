<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticatedRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        return checkRole($roles, $request->user()->role) ? $next($request) : abort(403);
    }
}
