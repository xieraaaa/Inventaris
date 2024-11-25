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
    Route::controller(BarangController::class)->group(function() {
        Route::get('barang', 'index')->name('barang');
        Route::get('get-barang', 'get');
        Route::get('get-barang-filtered', 'filtered_get');
        Route::get('getDatatables', 'getDatatables');
        Route::post('store-barang', 'store');
        Route::post('edit-barang', 'edit');
        Route::post('delete-barang', 'destroy');
        Route::post('detail-barang', 'detail')->name('barang.detail');
        Route::post('import-barang', 'import')->name('barang.import');
    });

    Route::controller(MerekController::class)->group(function() {
        Route::get('merek', 'index')->name('merek');
        Route::post('store-merek', 'store');
        Route::post('edit-merek', 'edit');
        Route::post('delete-merek', 'destroy');
        Route::post('merek/import', 'import')->name('merek.import');
    });

    Route::controller(PeminjamanController::class)->group(function() {
        Route::middleware('role:user')->group(function() {
            Route::get('riwayat', 'riwayat')->name('riwayat');
            Route::get('peminjaman/riwayat','history');
            Route::post('tambah-peminjaman', 'add');
        });
        
        Route::middleware('role:admin')->group(function() {
            Route::get('peminjaman', 'index')->name('peminjaman');
            Route::get('detal/Admin/{id}', 'detailAdmin');
            Route::post('/peminjaman/update-status/{id}', 'acceptPeminjaman');
            Route::post('/peminjaman/admin-status/{id}', 'acceptStatus');
            Route::post('/peminjaman/kembali-status/{id}', 'peminjamanKembali');
            Route::post('/peminjaman/reject/{id}', 'rejectPeminjaman');
        });

        Route::middleware('role:admin|superadmin')->group(function() {
            Route::get('peminjaman/detail', 'getDetails');
            Route::post('/peminjaman/update-status/{id}', 'acceptPeminjaman');
            Route::post('/peminjaman/reject/{id}', 'rejectPeminjaman');
        });
    });

    Route::controller(UnitController::class)->group(function() {
        Route::middleware('role:admin')->group(function() {
            Route::get('unit', 'index')->name('unit');
            Route::post('store-unit', 'store');
            Route::post('edit-unit', 'edit');
            Route::post('delete-unit', 'destroy');
            Route::post('unit/import', 'import')->name('unit.import');
        });
    });

    Route::controller(KategoriController::class)->group(function() {
        Route::get('kategori', 'index')->name('kategori');
        Route::post('store-kategori', 'store');
        Route::post('edit-kategori', 'edit');
        Route::post('delete-kategori', 'destroy');
        Route::post('/kategori/import', 'import')->name('kategori.import');
    });

    Route::controller(ProfileController::class)->group(function() {
        Route::get('profile', 'edit')   ->name('profile.edit');
        Route::post('profile', 'update') ->name('profile.update');
        Route::delete('profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(PemindahanController::class)->group(function() {
        Route::get('pemindahan', 'index')->name('pemindahan');
        Route::get('pemindahan/riwayat', 'viewriwayat')->name('pemindahan.riwayat');
        route::get('pemindahan/datariwayat','riwayat');
        Route::get('pemindahan/detail', 'getDetails');
        Route::post('store-pemindahan', 'store');
    });
});

Route::controller(SocialLoginController::class)->group(function() {
    Route::get('socialite/{driver}', 'toProvider')->where('driver', 'google');
    Route::get('auth/{driver}/login', 'handleCallback')->where('driver', 'google');
});
require __DIR__.'/auth.php';
