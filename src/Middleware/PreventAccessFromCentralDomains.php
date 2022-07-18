<?php

declare(strict_types=1);

namespace Stancl\Tenancy\Middleware;

use Closure;
use Hotash\Authable\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PreventAccessFromCentralDomains
{
    /**
     * Set this property if you want to customize the on-fail behavior.
     *
     * @var callable|null
     */
    public static $abortRequest;

    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $host = Str::after($host, Registrar::as());

        if (in_array($host, config('tenancy.central_domains'))) {
            $abortRequest = static::$abortRequest ?? function () {
                abort(404);
            };

            return $abortRequest($request, $next);
        }

        return $next($request);
    }
}
