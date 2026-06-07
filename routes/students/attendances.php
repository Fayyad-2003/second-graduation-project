<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/attendance', [\App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{class}', [\App\Http\Controllers\Student\AttendanceController::class, 'show'])->name('attendance.show');
});
