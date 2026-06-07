<?php

use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::get('report/{report}', [ReportController::class, 'show'])->name('report.show');
    // Change to POST since we are sending data
    Route::post('report/{report}/reply', [ReportController::class, 'reply'])->name('report.reply');
    Route::post('report/{report}/close', [ReportController::class, 'close'])->name('report.close');
});
