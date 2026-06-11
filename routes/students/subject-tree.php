
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\SubjectTreeController;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/subject-tree', [SubjectTreeController::class, 'index'])->name('subject-tree.index');
});
