<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudyProgramController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/study-program', [StudyProgramController::class, 'index'])->name('study-program.index');
    Route::post('/study-program', [StudyProgramController::class, 'store'])->name('study-program.store');
    Route::put('/study-program/{studyProgram}', [StudyProgramController::class, 'update'])->name('study-program.update');
    Route::delete('/study-program/{studyProgram}', [StudyProgramController::class, 'destroy'])->name('study-program.destroy');
});
