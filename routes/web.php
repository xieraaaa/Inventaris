<?php

use App\Http\Controllers\MerekController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('merek', [MerekController::class, 'index'])->name('merek');
Route::post('store-merek', [MerekController::class, 'store']);
Route::post('edit-merek', [MerekController::class, 'edit']);
Route::post('delete-merek', [MerekController::class, 'destroy']);

Route::get('unit', [UnitController::class, 'index'])->name('unit');
Route::post('store-unit', [UnitController::class, 'store']);
Route::post('edit-unit', [UnitController::class, 'edit']);
Route::post('delete-unit', [UnitController::class, 'destroy']);

require __DIR__.'/auth.php';
