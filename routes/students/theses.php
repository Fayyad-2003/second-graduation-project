<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/thesis', [\App\Http\Controllers\Student\ThesisController::class, 'index'])->name('thesis.index');
    Route::get('/thesis/create', [\App\Http\Controllers\Student\ThesisController::class, 'create'])->name('thesis.create');
    Route::post('/thesis', [\App\Http\Controllers\Student\ThesisController::class, 'store'])->name('thesis.store');
    Route::post('/thesis/supervision', [\App\Http\Controllers\Student\ThesisController::class, 'storeSupervision'])->name('thesis.supervision.store');
});
