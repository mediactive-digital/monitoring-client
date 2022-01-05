<?php

namespace MediactiveDigital\MonitoringClient\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringClientSecurity
{

    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (config('monitoring-client.enabled')) {

            $allowIps = config('monitoring-client.ip_filter');
           
            if (empty($allowIps) || !in_array($_SERVER['REMOTE_ADDR'], $allowIps)) {
                abort(403);
            }
            return $next($request);
        }else{
            abort(403);
        }
    }
}
