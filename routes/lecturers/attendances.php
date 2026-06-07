<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\AttendanceInputController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/attendance-input', [AttendanceInputController::class, 'index'])->name('attendance-input.index');
    Route::get('/attendance-input/class/{class}', [AttendanceInputController::class, 'showClass'])->name('attendance-input.class');
    Route::get('/attendance-input/class/{class}/meeting/create', [AttendanceInputController::class, 'createMeeting'])->name('attendance-input.meeting.create');
    Route::post('/attendance-input/class/{class}/meeting', [AttendanceInputController::class, 'storeMeeting'])->name('attendance-input.meeting.store');
    Route::get('/attendance-input/meeting/{meeting}/input', [AttendanceInputController::class, 'inputAttendance'])->name('attendance-input.input');
    Route::post('/attendance-input/meeting/{meeting}', [AttendanceInputController::class, 'storeAttendance'])->name('attendance-input.store');
});
