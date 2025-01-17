<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        Log::info('User Activity', [
            'user_id' => optional($request->user())->id,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'payload' => $request->all(),
            'timestamp' => now(),
        ]);

        return $response;
    }
}
