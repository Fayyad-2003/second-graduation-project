<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ThesisController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/thesis', [ThesisController::class, 'index'])->name('thesis.index');
    Route::get('/thesis/{thesis}', [ThesisController::class, 'show'])->name('thesis.show');
    Route::post('/thesis/{thesis}/assign-supervisor', [ThesisController::class, 'assignSupervisor'])->name('thesis.assign-supervisor');
    Route::put('/thesis/{thesis}/status', [ThesisController::class, 'updateStatus'])->name('thesis.update-status');
    Route::put('/thesis/{thesis}/grades', [ThesisController::class, 'updateGrades'])->name('thesis.update-grades');
});
