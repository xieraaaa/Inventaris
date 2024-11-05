<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\kategoriController;
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
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('merek', [MerekController::class, 'index'])->name('merek');
    Route::post('store-merek', [MerekController::class, 'store']);
    Route::post('edit-merek', [MerekController::class, 'edit']);
    Route::post('delete-merek', [MerekController::class, 'destroy']);
    Route::post('/merek/import', [MerekController::class, 'import'])->name('merek.import');

    Route::get('unit', [UnitController::class, 'index'])->name('unit');
    Route::post('store-unit', [UnitController::class, 'store']);
    Route::post('edit-unit', [UnitController::class, 'edit']);
    Route::post('delete-unit', [UnitController::class, 'destroy']);
    Route::post('/unit/import', [UnitController::class, 'import'])->name('unit.import');

    Route::get('kategori', [kategoriController::class, 'index'])->name('kategori');
    Route::post('store-kategori', [kategoriController::class, 'store']);
    Route::post('edit-kategori', [kategoriController::class, 'edit']);
    Route::post('delete-kategori', [kategoriController::class, 'destroy']);
    Route::post('/kategori/import', [kategoriController::class, 'import'])->name('kategori.import');

    Route::get('barang', [BarangController::class, 'index'])->name('barang');
    Route::post('store-barang', [BarangController::class, 'store']);
    Route::post('edit-barang', [BarangController::class, 'edit']);
    Route::post('delete-barang', [BarangController::class, 'destroy']);
    Route::post('import-barang', [BarangController::class, 'import'])->name('barang.import');
});

require __DIR__.'/auth.php';
