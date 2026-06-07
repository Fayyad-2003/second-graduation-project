<?php

use App\Http\Controllers\Student\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::get('report/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('report', [ReportController::class, 'store'])->name('report.store');
    Route::get('report/{report}', [ReportController::class, 'show'])->name('report.show');
});
