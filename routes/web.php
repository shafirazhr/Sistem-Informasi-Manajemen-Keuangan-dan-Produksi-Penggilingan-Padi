<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PgabahsController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggilinganController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SearchController;

// ================= LOGIN =================
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login',[AuthController::class,'formLogin'])->name('login');
Route::post('/login',[AuthController::class,'login']);
Route::get('/logout',[AuthController::class,'logout'])->name('logout');


// ================= ROUTE YANG BUTUH LOGIN =================
Route::middleware(['cekLogin'])->group(function () {

    Route::get('/search', [SearchController::class, 'index'])->name('search');

    Route::get('/dashboardPU',[DashboardController::class,'pemilik']);
    Route::get('/dashboardAdmin',[DashboardController::class,'admin']);

    Route::resource('produk', ProdukController::class);
    Route::resource('pgabahs', PgabahsController::class);

    Route::resource('penggajian', PenggajianController::class)
        ->only(['index','create','store','destroy']);

    Route::resource('pengeluaran', PengeluaranController::class)
        ->only(['index','create','store','destroy']);

    Route::resource('penggilingan', PenggilinganController::class);
    Route::resource('pembeli', PembeliController::class);
    Route::resource('penjualan', PenjualanController::class);

    Route::get('/penjualan/{id}/nota',[PenjualanController::class,'nota'])
        ->name('penjualan.nota');

    Route::prefix('laporan')->group(function(){

        Route::get('/', [LaporanController::class,'index'])->name('laporan.index');

        Route::get('/keseluruhan/pdf', [LaporanController::class,'keseluruhanPdf'])
            ->name('laporan.keseluruhan.pdf');

        Route::get('/keseluruhan/excel', [LaporanController::class,'keseluruhanExcel'])
            ->name('laporan.keseluruhan.excel');

        Route::get('/penjualan', [LaporanController::class,'penjualan'])
            ->name('laporan.penjualan');

        Route::get('/penjualan/pdf', [LaporanController::class,'penjualanPdf'])
            ->name('laporan.penjualan.pdf');

        Route::get('/penjualan/excel', [LaporanController::class,'penjualanExcel'])
            ->name('laporan.penjualan.excel');

        Route::get('/pengeluaran', [LaporanController::class,'pengeluaran'])
            ->name('laporan.pengeluaran');

        Route::get('/pengeluaran/pdf', [LaporanController::class,'pengeluaranPdf'])
            ->name('laporan.pengeluaran.pdf');

        Route::get('/pengeluaran/excel', [LaporanController::class,'pengeluaranExcel'])
            ->name('laporan.pengeluaran.excel');

    });

});

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\ProdukController;
// use App\Http\Controllers\PgabahsController;
// use App\Http\Controllers\PenggajianController;
// use App\Http\Controllers\PengeluaranController;
// use App\Http\Controllers\LaporanController;
// use App\Http\Controllers\PenggilinganController;
// use App\Http\Controllers\PembeliController;
// use App\Http\Controllers\PenjualanController;
// use App\Http\Controllers\SearchController;

// // LOGIN
// Route::get('/', function () {
//     return redirect('/login');
// });

// Route::get('/login',[AuthController::class,'formLogin'])->name('login');
// Route::post('/login',[AuthController::class,'login']);
// Route::get('/logout',[AuthController::class,'logout'])->name('logout');


// // ROUTE SETELAH LOGIN (TANPA MIDDLEWARE)
// Route::get('/search', [SearchController::class, 'index'])->name('search');

// Route::get('/dashboardPU',[DashboardController::class,'pemilik']);
// Route::get('/dashboardAdmin',[DashboardController::class,'admin']);

// Route::resource('produk', ProdukController::class);
// Route::resource('pgabahs', PgabahsController::class);

// Route::resource('penggajian', PenggajianController::class)
//     ->only(['index','create','store','destroy']);

// Route::resource('pengeluaran', PengeluaranController::class)
//     ->only(['index','create','store','destroy']);

// Route::resource('penggilingan', PenggilinganController::class);
// Route::resource('pembeli', PembeliController::class);
// Route::resource('penjualan', PenjualanController::class);

// Route::get('/penjualan/{id}/nota',[PenjualanController::class,'nota'])->name('penjualan.nota');

// Route::prefix('laporan')->group(function(){

//     Route::get('/', [LaporanController::class,'index'])->name('laporan.index');
//     Route::get('/keseluruhan/pdf', [LaporanController::class,'keseluruhanPdf'])->name('laporan.keseluruhan.pdf');
//     Route::get('/keseluruhan/excel', [LaporanController::class,'keseluruhanExcel'])->name('laporan.keseluruhan.excel');

//     Route::get('/penjualan', [LaporanController::class,'penjualan'])
//         ->name('laporan.penjualan');
//     Route::get('/penjualan/pdf', [LaporanController::class,'penjualanPdf'])
//         ->name('laporan.penjualan.pdf');
//     Route::get('/penjualan/excel', [LaporanController::class,'penjualanExcel'])
//         ->name('laporan.penjualan.excel');

//     Route::get('/pengeluaran', [LaporanController::class,'pengeluaran'])
//         ->name('laporan.pengeluaran');
//     Route::get('/pengeluaran/pdf', [LaporanController::class,'pengeluaranPdf'])
//         ->name('laporan.pengeluaran.pdf');
//     Route::get('/pengeluaran/excel', [LaporanController::class,'pengeluaranExcel'])
//         ->name('laporan.pengeluaran.excel');

// });