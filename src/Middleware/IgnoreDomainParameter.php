<?php

namespace Stancl\Tenancy\Middleware;

use Closure;
use Illuminate\Http\Request;

class IgnoreDomainParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->route()?->forgetParameter('domain');

        return $next($request);
    }
}
