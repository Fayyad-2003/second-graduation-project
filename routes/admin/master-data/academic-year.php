<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AcademicYearController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/academic-year', [AcademicYearController::class, 'index'])->name('academic-year.index');
    Route::post('/academic-year', [AcademicYearController::class, 'store'])->name('academic-year.store');
    Route::put('/academic-year/{academicYear}', [AcademicYearController::class, 'update'])->name('academic-year.update');
    Route::delete('/academic-year/{academicYear}', [AcademicYearController::class, 'destroy'])->name('academic-year.destroy');
    Route::get('/academic-year/active', [AcademicYearController::class, 'getActive'])->name('academic-year.active');
    Route::post('/academic-year/{academicYear}/activate', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
});
