<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        if (!auth() || !auth()->user()  || !auth()->user()->isAdmin) {
            return response()->json(['error' => true, 'data' => null], 405);
        }
        return $next($request);
    }

}
