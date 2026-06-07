<?php

use App\Http\Controllers\Admin\SemesterCalendarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin/semester-calendar')->name('admin.semester-calendar.')->group(function () {
    Route::get('/', [SemesterCalendarController::class, 'index'])->name('index');
    Route::post('/', [SemesterCalendarController::class, 'store'])->name('store');
    Route::put('/{semesterCalendar}', [SemesterCalendarController::class, 'update'])->name('update');
    Route::delete('/{semesterCalendar}', [SemesterCalendarController::class, 'destroy'])->name('destroy');
});
