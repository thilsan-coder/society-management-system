<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || $user->status !== 'active') {
            abort(403, 'Unauthorized access or inactive user account.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            // Handle comma or pipe separated strings if passed
            $splitRoles = preg_split('/[,|]/', $role);
            if ($user->hasRole($splitRoles)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this resource.');
    }
}
