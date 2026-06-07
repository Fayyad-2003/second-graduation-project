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
