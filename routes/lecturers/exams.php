<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\ExamController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/exam/{class}', [ExamController::class, 'index'])->name('exam.index');
    Route::post('/exam/{class}', [ExamController::class, 'store'])->name('exam.store');
    Route::delete('/exam/{class}/{exam}', [ExamController::class, 'destroy'])->name('exam.destroy');
    Route::get('/exam/{class}/{exam}/questions', [ExamController::class, 'manageQuestions'])->name('exam.questions');
    Route::put('/exam/{class}/{exam}/questions', [ExamController::class, 'syncQuestions'])->name('exam.questions.sync');
});
