<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\AttendanceController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/{attendance}/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
});
