<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\DashboardController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
