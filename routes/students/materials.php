<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/material/{class}', [\App\Http\Controllers\Student\MaterialController::class, 'index'])->name('material.index');
    Route::get('/material/{class}/download/{material}', [\App\Http\Controllers\Student\MaterialController::class, 'download'])->name('material.download');
});
