<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;

class LogPageView
{
    public function handle($request, Closure $next)
    {
        if (auth('admin')->check()) {
            ActivityLog::create([
                'admin_id'    => auth('admin')->id(),
                'action'      => 'viewed_page',
                'meta'        => [
                    'route' => optional($request->route())->getName(),
                    'path'  => $request->path(),
                    'ip'    => $request->ip(),
                ],
            ]);
        }
        return $next($request);
    }
}
