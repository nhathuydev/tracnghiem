<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

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
//        if ($request->route()->getAction()['prefix'] === '/report') {
//            return $next($request);
//        };
//        if (!auth() || !auth()->user()  || !auth()->user()->isAdmin) {
//            return response()->json(['error' => true, 'data' => null], 405);
//        }
        return $next($request);
    }

}
