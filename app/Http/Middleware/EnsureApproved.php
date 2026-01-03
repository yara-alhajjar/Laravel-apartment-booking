<?php

namespace App\Http\Middleware;

use Closure;

class EnsureApproved
{
    public function handle($request, Closure $next)
    {
        if (!$request->user() || $request->user()->approval_status !== 'approved') {
            return response()->json([
            'message' => 'Account not approved',
            'status'  => $request->user() ? $request->user()->approval_status : 'unknown'
            ], 403);
        }

        return $next($request);
    }
}
