<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class GeolocationCloudflare
{
    public function handle(Request $request, Closure $next)
    {

        if (!auth('sanctum')->check()) {
            $country = $request->header('CF-IPCountry', config('brands.top_list.default_country'));
            $request->query->set('country', $country);
        }

        return $next($request);
    }
}