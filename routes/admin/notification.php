<?php

use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('notifications')->name('notification.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
    });
});
