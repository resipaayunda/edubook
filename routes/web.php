<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\LaporanController;



//User
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\BukuUserController;
use App\Http\Controllers\User\UserPengembalianController;

Route::get('/', function () {
    return redirect()->route('login');
});


require __DIR__.'/auth.php';


Route::middleware('auth')->group(function () {

    // // Dashboard default (akan kita redirect nanti berdasarkan role)
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});



//ADMIN AREA

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])
        ->name('dashboard');

        // Kelola Anggota
        Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
        Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
        Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
        Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');

        // Data Buku
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

        // Transaksi
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::put('/transaksi/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

        // Kategori
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Pengembalian
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::put('/pengembalian/{id}', [PengembalianController::class, 'update'])->name('pengembalian.update');
        Route::delete('/pengembalian/{id}', [PengembalianController::class, 'destroy'])->name('pengembalian.destroy');

        // Laporan 
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');

    });


//USER AREA 

Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // Dashboard User
         Route::get('/dashboard', [App\Http\Controllers\User\UserDashboardController::class, 'index'])
        ->name('dashboard');

        // Daftar Buku 
        Route::get('/buku', [BukuUserController::class, 'index'])->name('buku.index');
        Route::post('/buku', [BukuUserController::class, 'store'])->name('buku.store');

        // Pengembalian
        Route::get('/pengembalian', [UserPengembalianController::class, 'index'])
            ->name('pengembalian.index');

        Route::post('/pengembalian/{id}', [UserPengembalianController::class, 'kembalikan'])
            ->name('pengembalian.kembalikan');

    });