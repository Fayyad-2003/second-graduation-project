<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has the required role
        $hasAccess = match ($role) {
            'admin', 'admin_faculty' => in_array($user->role, ['superadmin', 'admin', 'admin_faculty']),
            'superadmin' => $user->role === 'superadmin',
            'lecturer' => $user->role === 'lecturer',
            'student' => $user->role === 'student',
            default => $user->role === $role,
        };

        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
