<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\AssignmentController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/assignment/{class}', [AssignmentController::class, 'index'])->name('assignment.index');
    Route::post('/assignment/{class}', [AssignmentController::class, 'store'])->name('assignment.store');
    Route::get('/assignment/{class}/{assignment}', [AssignmentController::class, 'show'])->name('assignment.show');
    Route::post('/assignment/{class}/submission/{submission}/grade', [AssignmentController::class, 'grade'])->name('assignment.grade');
    Route::post('/assignment/{class}/{assignment}/toggle', [AssignmentController::class, 'toggle'])->name('assignment.toggle');
    Route::delete('/assignment/{class}/{assignment}', [AssignmentController::class, 'destroy'])->name('assignment.destroy');
    Route::get('/assignment/{class}/submission/{submission}/download', [AssignmentController::class, 'downloadSubmission'])->name('assignment.submission.download');
});
