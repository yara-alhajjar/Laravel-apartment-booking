<?php

namespace App\Http\Middleware;

use Closure;

class EnsureRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user() || $request->user()->role !== $role) {
            return response()->json(['message' => 'Unauthorized role'], 403);
        }

        return $next($request);
    }
}
