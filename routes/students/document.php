<?php

use App\Http\Controllers\Student\DocumentApplicationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('document-application', [DocumentApplicationController::class, 'index'])->name('document-application.index');
    Route::post('document-application', [DocumentApplicationController::class, 'store'])->name('document-application.store');
});
