<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\MaterialController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/material/{class}', [MaterialController::class, 'index'])->name('material.index');
    Route::post('/material/{class}', [MaterialController::class, 'store'])->name('material.store');
    Route::put('/material/{class}/{material}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/material/{class}/{material}', [MaterialController::class, 'destroy'])->name('material.destroy');
    Route::get('/material/{class}/download/{material}', [MaterialController::class, 'download'])->name('material.download');

    // AI Features
    Route::post('/material/ai-generate/{material}', [\App\Http\Controllers\Lecturer\MaterialAiController::class, 'generate'])->name('material.ai-generate');
});
