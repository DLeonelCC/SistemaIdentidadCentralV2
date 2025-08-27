<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CustomCan
{
    public function handle($request, Closure $next, $permission)
    {
        $user = User::withoutGlobalScopes()->find(Auth::id());

        if (!$user) {
            abort(403, 'No autorizado');
        }

        $roles = Role::withoutGlobalScopes()->get();

        foreach ($roles as $role) {
            if ($role->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        abort(403, 'No autorizado');
    }
}
