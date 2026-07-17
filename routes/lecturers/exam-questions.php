<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\ExamQuestionController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::post('/exam/{class}/questions/ai-generate', [ExamQuestionController::class, 'aiGenerate'])->name('exam-questions.ai-generate');
    Route::get('/exam/{class}/questions', [ExamQuestionController::class, 'index'])->name('exam-questions.index');
    Route::get('/exam/{class}/questions/create', [ExamQuestionController::class, 'create'])->name('exam-questions.create');
    Route::post('/exam/{class}/questions', [ExamQuestionController::class, 'store'])->name('exam-questions.store');
    Route::get('/exam/{class}/questions/{question}/edit', [ExamQuestionController::class, 'edit'])->name('exam-questions.edit');
    Route::put('/exam/{class}/questions/{question}', [ExamQuestionController::class, 'update'])->name('exam-questions.update');
    Route::delete('/exam/{class}/questions/{question}', [ExamQuestionController::class, 'destroy'])->name('exam-questions.destroy');
});
