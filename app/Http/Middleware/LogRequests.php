<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $logData = [
            'method'  => $request->method(),
            'url'     => $request->fullUrl(),
            'ip'      => $request->ip(),
            'headers' => json_encode($request->headers->all()),
            'body'    => json_encode($request->all()),
        ];

        Log::info('Incoming Request', $logData);

        RequestLog::create($logData);

        return $next($request);
    }
}
