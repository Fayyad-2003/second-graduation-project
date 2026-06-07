<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\SupervisionController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/supervision', [SupervisionController::class, 'index'])->name('supervision.index');
    Route::get('/supervision/study-plan-approval', [SupervisionController::class, 'studyPlanApproval'])->name('supervision.study-plan-approval');
    Route::get('/supervision/study-plan/{study_plan}', [SupervisionController::class, 'showStudyPlan'])->name('supervision.study-plan-show');
    Route::post('/supervision/study-plan/{study_plan}/approve', [SupervisionController::class, 'approveStudyPlan'])->name('supervision.study-plan-approve');
    Route::post('/supervision/study-plan/{study_plan}/reject', [SupervisionController::class, 'rejectStudyPlan'])->name('supervision.study-plan-reject');
});
