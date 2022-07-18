<?php

declare(strict_types=1);

namespace Stancl\Tenancy\Middleware;

use Closure;
use Hotash\Authable\Registrar;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class InitializeTenancyByDomainOrSubdomain
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
        $host = Str::after($request->getHost(), Registrar::as());

        URL::defaults(['domain' => $host]);

        // Skip for central domains
        if (in_array($host, config('tenancy.central_domains'), true)) {
            return $next($request);
        }

        if ($this->isSubdomain($host)) {
            return app()->make(InitializeTenancyBySubdomain::class)->handle($request, $next);
        } else {
            return app()->make(InitializeTenancyByDomain::class)->handle($request, $next);
        }
    }

    protected function isSubdomain(string $hostname): bool
    {
        return Str::endsWith($hostname, config('tenancy.central_domains'));
    }
}
