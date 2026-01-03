<?php

namespace App\Http\Middleware;

use Closure;

class EnsureAnyRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Unauthorized role'], 403);
        }

        return $next($request);
    }
}
 