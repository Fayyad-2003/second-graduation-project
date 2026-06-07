<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClassController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/class', [ClassController::class, 'index'])->name('class.index');
    Route::post('/class', [ClassController::class, 'store'])->name('class.store');
    Route::put('/class/{class}', [ClassController::class, 'update'])->name('class.update');
    Route::delete('/class/{class}', [ClassController::class, 'destroy'])->name('class.destroy');
});
