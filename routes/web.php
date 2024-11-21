<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ {
    BarangController,
    DashboardController,
    KategoriController,
    MerekController,
    PemindahanController,
    PeminjamanController,
    ProfileController,
    SocialLoginController,
    UnitController,
};

Route::get('/', function () {
	return view('auth.login');
});

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function() {
        Route::get('barang',   [BarangController::class, 'index'])  ->name('barang');
        Route::get('merek',    [MerekController::class, 'index'])   ->name('merek');
        Route::get('unit',     [UnitController::class, 'index'])    ->name('unit');
        Route::get('kategori', [kategoriController::class, 'index'])->name('kategori');

        Route::post('store-merek',  [MerekController::class, 'store']);
        Route::post('edit-merek',   [MerekController::class, 'edit']);
        Route::post('delete-merek', [MerekController::class, 'destroy']);
        Route::post('merek/import', [MerekController::class, 'import'])->name('merek.import');

        Route::post('store-unit',  [UnitController::class, 'store']);
        Route::post('edit-unit',   [UnitController::class, 'edit']);
        Route::post('delete-unit', [UnitController::class, 'destroy']);
        Route::post('unit/import', [UnitController::class, 'import'])->name('unit.import');

        Route::post('store-kategori',   [kategoriController::class, 'store']);
        Route::post('edit-kategori',    [kategoriController::class, 'edit']);
        Route::post('delete-kategori',  [kategoriController::class, 'destroy']);
        Route::post('/kategori/import', [kategoriController::class, 'import'])->name('kategori.import');

        Route::get('peminjaman',       [PeminjamanController::class, 'index'])->name('minjam');
        Route::get('detal/Admin/{id}', [PeminjamanController::class, 'detailAdmin']);
        Route::get('/items/{code}',    [PeminjamanController::class, 'show']);
        Route::post('/store-item',     [PeminjamanController::class, 'store']);
        Route::post('edit-item',       [PeminjamanController::class, 'edit']);
       
        Route::post('store-kategori',  [kategoriController::class, 'store']);
        Route::post('edit-kategori',   [kategoriController::class, 'edit']);
        Route::post('delete-kategori', [kategoriController::class, 'destroy']);
        Route::post('kategori/import', [kategoriController::class, 'import'])->name('kategori.import');
    });

    Route::controller(PeminjamanController::class)->group(function() {
        Route::middleware('role:user')->group(function() {
            Route::get('riwayat', 'riwayat')->name('riwayat');
            Route::get('peminjaman/riwayat','history');
            Route::post('tambah-peminjaman', 'add');
        });

        Route::middleware('role:admin')->group(function() {
            Route::get('peminjaman/detail', 'getDetails');
            Route::post('/peminjaman/update-status/{id}', 'acceptPeminjaman');
            Route::post('/peminjaman/admin-status/{id}', 'acceptStatus');
            Route::post('/peminjaman/kembali-status/{id}', 'peminjamanKembali');
            Route::post('/peminjaman/reject/{id}', 'rejectPeminjaman');
        });
    });

    Route::controller(ProfileController::class)->group(function() {
        Route::get('profile', 'edit')   ->name('profile.edit');
        Route::post('profile', 'update') ->name('profile.update');
        Route::delete('profile', 'destroy')->name('profile.destroy');
    });

	Route::get('get-barang',     [BarangController::class, 'get']);
    Route::get('getDatatables',  [BarangController::class, 'getDatatables']);
	Route::post('store-barang',  [BarangController::class, 'store']);
	Route::post('edit-barang',   [BarangController::class, 'edit']);
	Route::post('delete-barang', [BarangController::class, 'destroy']);
	Route::post('detail-barang', [BarangController::class, 'detail'])->name('barang.detail');
	Route::post('import-barang', [BarangController::class, 'import'])->name('barang.import');

    Route::get('pemindahan', [PemindahanController::class, 'index'])->name( 'pemindahan');
    Route::post('store-pemindahan', [PemindahanController::class, 'store']);
});

Route::controller(SocialLoginController::class)->group(function() {
    Route::get('socialite/{driver}', 'toProvider')->where('driver', 'google');
    Route::get('auth/{driver}/login', 'handleCallback')->where('driver', 'google');
});
require __DIR__.'/auth.php';
