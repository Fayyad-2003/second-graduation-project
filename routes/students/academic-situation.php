<?php

use App\Http\Controllers\Student\AcademicSituationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students/academic-situation')->name('students.academic-situation.')->group(function () {
    Route::get('/', [AcademicSituationController::class, 'index'])->name('index');
    Route::post('/generate-recommendations', [AcademicSituationController::class, 'generateRecommendations'])->name('generate-recommendations');
});
