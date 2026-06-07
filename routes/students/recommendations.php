<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\RecommendationController;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
});
