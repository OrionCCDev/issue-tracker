<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // CM, GM and PM have access to create projects
        if (in_array($user->role, ['cm', 'gm', 'pm']) && str_contains($request->path(), 'projects/create')) {
            return $next($request);
        }

        // CM and GM have access to all projects and data
        if (in_array($user->role, ['cm', 'gm']) && !str_contains($request->path(), 'users')) {
            return $next($request);
        }

        $roles = explode('|', $role);

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
