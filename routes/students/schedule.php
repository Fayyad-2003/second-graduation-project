<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/schedule', [\App\Http\Controllers\Student\ScheduleController::class, 'index'])->name('schedule.index');
});
