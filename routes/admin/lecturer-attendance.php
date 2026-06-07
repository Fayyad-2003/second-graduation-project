<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LecturerAttendanceController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/lecturer-attendance', [LecturerAttendanceController::class, 'index'])->name('lecturer-attendance.index');
    Route::get('/lecturer-attendance/{lecturer}', [LecturerAttendanceController::class, 'show'])->name('lecturer-attendance.show');
});
