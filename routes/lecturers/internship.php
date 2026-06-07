<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lecturer\InternshipController;

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturers')->name('lecturers.')->group(function () {
   Route::get('/internship', [InternshipController::class, 'index'])->name('internship.index');
    Route::get('/internship/{internship}', [InternshipController::class, 'show'])->name('internship.show');
    Route::post('/internship/logbook/{logbook}/review', [InternshipController::class, 'reviewLogbook'])->name('internship.logbook.review');
    Route::put('/internship/{internship}/status', [InternshipController::class, 'updateStatus'])->name('internship.update-status');
});
