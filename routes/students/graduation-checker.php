<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\GraduationCheckerController;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/graduation-checker', [GraduationCheckerController::class, 'index'])->name('graduation-checker.index');
});
