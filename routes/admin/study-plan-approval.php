<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudyPlanApprovalController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/study-plan-approval', [StudyPlanApprovalController::class, 'index'])->name('study-plan-approval.index');
    Route::get('/study-plan-approval/{study_plan}', [StudyPlanApprovalController::class, 'show'])->name('study-plan-approval.show');
});
