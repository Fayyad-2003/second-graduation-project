<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\LmsController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/lms', [LmsController::class, 'index'])->name('lms.index');
});
