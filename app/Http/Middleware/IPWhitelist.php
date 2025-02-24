<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IPWhitelist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIPs = ['5.191.247.58','10.2.84.140']; // İcazə verilən IP ünvanları

        if (!in_array($request->ip(), $allowedIPs)) {
            return response('İcazəniz yoxdur!', 403);
        }
        return $next($request);
    }
}
