<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/store-item', [PeminjamanController::class, 'store'])->middleware(['auth', 'verified']);
Route::post('edit-item', [PeminjamanController::class, 'edit']);

Route::get('/items/{code}', [PeminjamanController::class, 'show']);

Route::get('getDatatables', [BarangController::class, 'getDatatables'])->middleware(['role:user|admin']);

Route::middleware(['auth', 'role:admin'])->group(function() {
	Route::get('barang', [BarangController::class, 'index'])->name('barang');
	Route::get('merek', [MerekController::class, 'index'])->name('merek');
	Route::get('unit', [UnitController::class, 'index'])->name('unit');
	Route::get('kategori', [kategoriController::class, 'index'])->name('kategori');
});

Route::get('riwayat',  [PeminjamanController::class, 'riwayat'])->name('riwayat');

Route::get('peminjaman/detail/{id}', [PeminjamanController::class, 'getDetails']);

Route::middleware('auth')->group(function () {
	Route::get('profile',    [ProfileController::class, 'edit'])->name('profile.edit');
	Route::post('profile',   [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

	Route::post('store-merek',   [MerekController::class, 'store']);
	Route::post('edit-merek',    [MerekController::class, 'edit']);
	Route::post('delete-merek',  [MerekController::class, 'destroy']);
	Route::post('/merek/import', [MerekController::class, 'import'])->name('merek.import');

	Route::post('store-unit',   [UnitController::class, 'store']);
	Route::post('edit-unit',    [UnitController::class, 'edit']);
	Route::post('delete-unit',  [UnitController::class, 'destroy']);
	Route::post('/unit/import', [UnitController::class, 'import'])->name('unit.import');

	Route::post('store-kategori',   [kategoriController::class, 'store']);
	Route::post('edit-kategori',    [kategoriController::class, 'edit']);
	Route::post('delete-kategori',  [kategoriController::class, 'destroy']);
	Route::post('/kategori/import', [kategoriController::class, 'import'])->name('kategori.import');

	Route::post('store-barang',  [BarangController::class, 'store']);
	Route::post('edit-barang',   [BarangController::class, 'edit']);
	Route::post('delete-barang', [BarangController::class, 'destroy']);
	Route::get('get-barang',     [BarangController::class, 'get']);
	Route::post('detail-barang', [BarangController::class, 'detail'])->name('barang.detail');
	Route::post('import-barang', [BarangController::class, 'import'])->name('barang.import');

	Route::get('peminjaman',         [PeminjamanController::class, 'get'])->name('minjam');
	Route::post('tambah-peminjaman', [PeminjamanController::class, 'add']);
});

/* Rute-rute Testing */
Route::prefix('test')->group(function()
{
	Route::get('user/barang', [UserController::class, 'getUserDashboard']);
});

// Route::get('/peminjaman', [PeminjamanController::class, 'get']);
Route::post('/peminjaman/update-status/{id}', [PeminjamanController::class, 'updateStatus']);

Route::get('/socialite/{driver}', [SocialLoginController::class, 'toProvider'])->where('driver', 'google');
Route::get('/auth/{driver}/login', [SocialLoginController::class, 'handleCallback'])->where('driver', 'google');
require __DIR__.'/auth.php';
