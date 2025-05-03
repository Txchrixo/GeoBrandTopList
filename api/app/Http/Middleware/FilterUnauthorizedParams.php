<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FilterUnauthorizedParams
{
    private const RESTRICTED_FILTERS = ['active'];

    public function handle(Request $request, Closure $next)
    {
       
        if(!auth('sanctum')->check()) {
            $this->scrubQueryParams($request, self::RESTRICTED_FILTERS);
        }

        return $next($request);
    }

    private function scrubQueryParams(Request $request, array $filters): void
    {
        $query = $request->query->all();

        foreach ($filters as $filter) {
            unset($query[$filter]);
        }

        $request->query->replace($query);
    }
}