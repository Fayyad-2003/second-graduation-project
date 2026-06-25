<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login page
Route::get('/', fn() => redirect()->route('login'));

// Redirect generic dashboard to role-specific dashboard
Route::get('/dashboard', function () {
    $routes = [
        'superadmin'     => 'admin.dashboard',
        'admin'          => 'admin.dashboard',
        'admin_faculty'  => 'admin.dashboard',
        'lecturer'       => 'lecturers.dashboard',
        'student'        => 'students.dashboard',
    ];

    $role = auth()->user()->role;

    return redirect()->route($routes[$role] ?? 'login');
})->middleware(['auth', 'verified'])->name('dashboard');


// Language Switch Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        cookie()->queue('locale', $locale, 60 * 24 * 365); // 1 year
        session()->flash('success', __('Language changed successfully!'));
    }
    return redirect()->back();
})->name('lang.switch');

// Test route for locale
Route::get('/test-locale', function () {
    return response()->json([
        'app_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'cookie_locale' => request()->cookie('locale'),
        'config_locale' => config('app.locale'),
        'dir' => str_starts_with(app()->getLocale(), 'ar') ? 'rtl' : 'ltr',
    ]);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/health.php';
require __DIR__ . '/notification.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/admin/index.php';
require __DIR__ . '/lecturers/index.php';
require __DIR__ . '/students/index.php';

// Shared Chat Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{chatRequest}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chatRequest}/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
});
