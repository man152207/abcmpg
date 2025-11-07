<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DepartmentGate
{
    public function handle($request, Closure $next, ...$departments)
    {
        $user = Auth::guard('admin')->user();
        if (!$user) {
            abort(401);
        }

        // If user has any of the required departments
        if ($user->departments()->whereIn('name', $departments)->exists()) {
            return $next($request);
        }

        abort(403, 'Access denied for your department.');
    }
}
