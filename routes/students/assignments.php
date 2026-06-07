<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/assignment/{class}', [\App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('assignment.index');
    Route::get('/assignment/{class}/{assignment}', [\App\Http\Controllers\Student\AssignmentController::class, 'show'])->name('assignment.show');
    Route::post('/assignment/{class}/{assignment}/submit', [\App\Http\Controllers\Student\AssignmentController::class, 'submit'])->name('assignment.submit');
    Route::get('/assignment/{class}/{assignment}/download', [\App\Http\Controllers\Student\AssignmentController::class, 'download'])->name('assignment.download');
});
