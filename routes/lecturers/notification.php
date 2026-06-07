<?php

use App\Http\Controllers\Lecturer\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::prefix('notifications')->name('notification.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
    });
});
