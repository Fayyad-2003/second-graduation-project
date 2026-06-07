<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InternshipController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/internship', [InternshipController::class, 'index'])->name('internship.index');
    Route::get('/internship/{internship}', [InternshipController::class, 'show'])->name('internship.show');
    Route::post('/internship/{internship}/assign-supervisor', [InternshipController::class, 'assignSupervisor'])->name('internship.assign-supervisor');
    Route::put('/internship/{internship}/status', [InternshipController::class, 'updateStatus'])->name('internship.update-status');
    Route::put('/internship/{internship}/grades', [InternshipController::class, 'updateGrades'])->name('internship.update-grades');
});
