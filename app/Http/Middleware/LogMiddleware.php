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

        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::info($request->headers);
        Log::info(111);

//        Log::info('Url: ' . $request->getUri());
//        Log::info('Client Ip: ' . $request->getClientIp());
//        Log::info('Ip: ' . $request->ip());
//        Log::info('Token: ' . $request->bearerToken());
//        Log::info('=======================');
    }
}
