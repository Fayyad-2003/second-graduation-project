<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/export/transcript', [\App\Http\Controllers\Student\ExportController::class, 'transcript'])->name('export.transcript');
    Route::get('/export/grades/{academicYear}', [\App\Http\Controllers\Student\ExportController::class, 'gradeReport'])->name('export.grades');
});
