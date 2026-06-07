<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\ChatRequestController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/chat-requests', [ChatRequestController::class, 'index'])->name('chat-requests.index');
    Route::patch('/chat-requests/{chatRequest}', [ChatRequestController::class, 'update'])->name('chat-requests.update');
});
