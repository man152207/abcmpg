<?php

namespace App\Http\Middleware;

use Closure;

class EnsureReception
{
    public function handle($request, Closure $next)
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        // super admin ?
        $isSuper = optional(\App\Models\UserPrivilege::select('full_or_partial')
            ->where('user_id', $admin->id)->first())->full_or_partial ?? 0;

        $inReception = method_exists($admin,'inReceptionDept')
            ? $admin->inReceptionDept()
            : \DB::table('admin_department')
                ->join('departments','admin_department.department_id','=','departments.id')
                ->where('admin_department.admin_id',$admin->id)
                ->where(function($q){ $q->where('departments.slug','reception')->orWhere('departments.name','Reception'); })
                ->exists();

        if ($isSuper || $inReception) {
            return $next($request);
        }
        abort(403,'Only Admin or Reception department can access this area.');
    }
}
