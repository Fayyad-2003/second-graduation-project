<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/grade-report', [\App\Http\Controllers\Student\GradeReportController::class, 'index'])->name('grade-report.index');
    Route::get('/grade-report/{academicYear}', [\App\Http\Controllers\Student\GradeReportController::class, 'show'])->name('grade-report.show');
});
