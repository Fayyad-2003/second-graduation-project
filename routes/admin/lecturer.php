<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LecturerController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/lecturer/export', [LecturerController::class, 'export'])->name('lecturer.export');
    Route::get('/lecturer', [LecturerController::class, 'index'])->name('lecturer.index');
    Route::post('/lecturer', [LecturerController::class, 'store'])->name('lecturer.store');
    Route::get('/lecturer/{lecturer}', [LecturerController::class, 'show'])->name('lecturer.show');
    Route::put('/lecturer/{lecturer}', [LecturerController::class, 'update'])->name('lecturer.update');
    Route::delete('/lecturer/{lecturer}', [LecturerController::class, 'destroy'])->name('lecturer.destroy');
});
