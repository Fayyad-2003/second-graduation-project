<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\ThesisController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
    Route::get('/thesis', [ThesisController::class, 'index'])->name('thesis.index');
    Route::get('/thesis/{thesis}', [ThesisController::class, 'show'])->name('thesis.show');
    Route::post('/thesis/supervision/{supervision}/review', [ThesisController::class, 'reviewSupervision'])->name('thesis.supervision.review');
    Route::put('/thesis/{thesis}/status', [ThesisController::class, 'updateStatus'])->name('thesis.update-status');
});
