<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/internship', [\App\Http\Controllers\Student\InternshipController::class, 'index'])->name('internship.index');
    Route::get('/internship/create', [\App\Http\Controllers\Student\InternshipController::class, 'create'])->name('internship.create');
    Route::post('/internship', [\App\Http\Controllers\Student\InternshipController::class, 'store'])->name('internship.store');
    Route::post('/internship/logbook', [\App\Http\Controllers\Student\InternshipController::class, 'storeLogbook'])->name('internship.logbook.store');
});
