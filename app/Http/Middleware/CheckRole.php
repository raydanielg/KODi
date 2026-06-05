<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $allowedRoles = explode(',', implode(',', $roles));

        if (!in_array(auth()->user()->role, $allowedRoles)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Required role: ' . implode(', ', $allowedRoles)], 403);
        }

        return $next($request);
    }
}
