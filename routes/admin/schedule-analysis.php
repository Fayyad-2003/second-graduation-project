<?php

use App\Http\Controllers\Admin\ScheduleAnalysisController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/schedule-analysis', [ScheduleAnalysisController::class, 'index'])->name('schedule-analysis.index');
});
