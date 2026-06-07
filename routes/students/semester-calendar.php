<?php

use App\Http\Controllers\Student\SemesterCalendarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students/semester-calendar')->name('students.semester-calendar.')->group(function () {
    Route::get('/', [SemesterCalendarController::class, 'index'])->name('index');
});
