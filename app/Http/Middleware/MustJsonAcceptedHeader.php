<?php

namespace App\Http\Middleware;

use Closure;

class MustJsonAcceptedHeader
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
        return !$request->wantsJson()
            ? response()->json(['code' => 403, 'message' => 'The request header must accepted : Application/json'])
            : $next($request);
    }
}
