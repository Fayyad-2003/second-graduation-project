<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/lms', [\App\Http\Controllers\Student\LmsController::class, 'index'])->name('lms.index');
    Route::post('/lms/chat-request/{class}', [\App\Http\Controllers\Student\ChatRequestController::class, 'store'])->name('lms.chat-request.store');
});
