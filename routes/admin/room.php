<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoomController;

Route::middleware(['auth', 'role:admin', 'faculty.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/room', [RoomController::class, 'index'])->name('room.index');
    Route::post('/room', [RoomController::class, 'store'])->name('room.store');
    Route::put('/room/{room}', [RoomController::class, 'update'])->name('room.update');
    Route::delete('/room/{room}', [RoomController::class, 'destroy'])->name('room.destroy');
});
