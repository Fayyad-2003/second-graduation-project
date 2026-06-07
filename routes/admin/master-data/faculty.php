<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FacultyController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/faculty', [FacultyController::class, 'index'])->name('faculty.index');
    Route::post('/faculty', [FacultyController::class, 'store'])->name('faculty.store');
    Route::put('/faculty/{faculty}', [FacultyController::class, 'update'])->name('faculty.update');
    Route::delete('/faculty/{faculty}', [FacultyController::class, 'destroy'])->name('faculty.destroy');
    
    // Faculty Credits Requirements
    Route::get('/faculty/{faculty}/requirements', [FacultyController::class, 'requirements'])->name('faculty.requirements');
    Route::put('/faculty/{faculty}/requirements', [FacultyController::class, 'updateRequirements'])->name('faculty.update_requirements');
});
