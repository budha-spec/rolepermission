<?php

namespace Budhaspec\Rolepermission\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Budhaspec\Rolepermission\Models\Module;
use Budhaspec\Rolepermission\Models\Permission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Optional: Allow unauthenticated routes
        if (!$user || !$user->role_id) {
            abort(403, 'Access denied');
        }

        if (! $user || ! $user->role || ! $user->role->permissions) {
            abort(403, 'Access denied: No permissions assigned.');
        }

        // Get current route name
        $currentRoute = $request->route()?->getName();

        if (! $currentRoute) {
            abort(403, 'Access denied: Unnamed route.');
        }

        // Optional: normalize route to permission key
        // Example: 'admin.products.index' → 'admin.products.index'
        $hasPermission = $user->role->permissions->contains('slug', $currentRoute);

        if (! $hasPermission) {
            abort(403, 'Access denied: Permission not granted.');
        }

        return $next($request);
    }
}
