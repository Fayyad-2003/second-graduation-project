<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\GradingController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/grading', [GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/{class}', [GradingController::class, 'show'])->name('grading.show');
    Route::post('/grading/{class}', [GradingController::class, 'store'])->middleware('throttle:grading')->name('grading.store');
});
