<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\GpaWarningController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/student/export', [StudentController::class, 'export'])->name('student.export');
    Route::get('/student', [StudentController::class, 'index'])->name('student.index');
    Route::post('/student', [StudentController::class, 'store'])->name('student.store');
    Route::get('/student/{student}', [StudentController::class, 'show'])->name('student.show');
    Route::put('/student/{student}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/student/{student}', [StudentController::class, 'destroy'])->name('student.destroy');

    // GPA Warning
    Route::get('/gpa-warning', [GpaWarningController::class, 'index'])->name('gpa-warning.index');
    Route::post('/gpa-warning/notify', [GpaWarningController::class, 'notify'])->name('gpa-warning.notify');
});
