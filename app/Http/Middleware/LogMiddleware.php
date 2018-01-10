<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class LogMiddleware
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
        Log::info($request->headers);
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    public function terminate($request, $response)
    {
//        Log::info('Url: ' . $request->getUri());
//        Log::info('Client Ip: ' . $request->getClientIp());
//        Log::info('Ip: ' . $request->ip());
//        Log::info('Token: ' . $request->bearerToken());
//        Log::info('=======================');
    }
}
