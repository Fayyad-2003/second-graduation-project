<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudyPlanSettingsController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/study-plan-settings', [StudyPlanSettingsController::class, 'index'])->name('study-plan-settings.index');
    Route::post('/study-plan-settings', [StudyPlanSettingsController::class, 'store'])->name('study-plan-settings.store');
    Route::put('/study-plan-settings/{gpa_credit_rule}', [StudyPlanSettingsController::class, 'update'])->name('study-plan-settings.update');
    Route::delete('/study-plan-settings/{gpa_credit_rule}', [StudyPlanSettingsController::class, 'destroy'])->name('study-plan-settings.destroy');
    Route::post('/study-plan-settings/reset-defaults', [StudyPlanSettingsController::class, 'resetDefaults'])->name('study-plan-settings.reset-defaults');
});
