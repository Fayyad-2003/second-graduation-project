<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['login' => __('Authentication failed.')]);
        }

        $roleMap = [
            'student' => 'student',
            'lecturer' => 'lecturer',
            'faculty_admin' => 'admin_faculty',
            'admin' => 'admin_faculty',
            'admin_faculty' => 'admin_faculty',
            'superadmin' => 'superadmin',
            // Indonesian mapping
            'student' => 'student',
            'lecturer' => 'lecturer',
            'admin_faculty' => 'admin_faculty',
        ];

        $roleKey = strtolower(trim($user->role ?? ''));
        $roleKey = str_replace(['-', ' '], '_', $roleKey);

        if (isset($roleMap[$roleKey])) {
            $normalizedRole = $roleMap[$roleKey];
            if ($user->role !== $normalizedRole) {
                $user->role = $normalizedRole;
                $user->save();
            }
        } else {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['login' => __('Your account does not have a valid role. Please contact administrator. Role: ' . $user->role)])
                ->withInput($request->only('email', 'remember'));
        }

        // Redirect based on role
        $redirectRoute = match ($user->role) {
            'student' => 'students.dashboard',
            'lecturer' => 'lecturers.dashboard',
            'superadmin', 'admin', 'admin_faculty' => 'admin.dashboard',
            default => 'dashboard',
        };

        return redirect()->intended(route($redirectRoute, absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
