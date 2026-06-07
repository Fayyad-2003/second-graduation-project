<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudyPlanController;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/study-plan', [StudyPlanController::class, 'index'])->name('study-plan.index');
    Route::post('/study-plan', [StudyPlanController::class, 'store'])->middleware('throttle:study_plans')->name('study-plan.store');
    Route::delete('/study-plan/{detailId}', [StudyPlanController::class, 'destroy'])->middleware('throttle:study_plans')->name('study-plan.destroy');
    Route::post('/study-plan/submit', [StudyPlanController::class, 'submit'])->middleware('throttle:study_plans')->name('study-plan.submit');
    Route::post('/study-plan/revise', [StudyPlanController::class, 'revise'])->middleware('throttle:study_plans')->name('study-plan.revise');
    Route::post('/study-plan/waitlist/{classId}', [StudyPlanController::class, 'waitlistToggle'])->name('study-plan.waitlist');
});
