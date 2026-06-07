<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\TranscriptController;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript.index');
});
