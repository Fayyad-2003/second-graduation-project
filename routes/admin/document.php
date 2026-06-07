<?php

use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\DocumentApplicationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    // Document Types
    Route::resource('document-type', DocumentTypeController::class)->except(['create', 'edit', 'show']);
    
    // Document Applications
    Route::get('document-application', [DocumentApplicationController::class, 'index'])->name('document-application.index');
    Route::get('document-application/{application}', [DocumentApplicationController::class, 'show'])->name('document-application.show');
    Route::put('document-application/{application}/status', [DocumentApplicationController::class, 'updateStatus'])->name('document-application.update-status');
});
