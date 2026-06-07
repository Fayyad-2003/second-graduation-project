<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CourseController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/course/export', [CourseController::class, 'export'])->name('course.export');
    Route::get('/course', [CourseController::class, 'index'])->name('course.index');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');
    Route::put('/course/{course}', [CourseController::class, 'update'])->name('course.update');
    Route::delete('/course/{course}', [CourseController::class, 'destroy'])->name('course.destroy');
});
