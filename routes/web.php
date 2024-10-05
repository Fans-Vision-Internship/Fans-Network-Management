<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::middleware(['auth'])->group(function () {
Route::resource('users', UserController::class);
Route::get('/home', [UserController::class, 'index']);
Route::resource('reseller', ResellerController::class);
Route::get('laporan', [PembayaranController::class, 'index'])->name('pembayaran.index');
Route::get('/export-pembayaran', [PembayaranController::class, 'exportExcel'])->name('export.pembayaran');
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/{reseller}', [TransaksiController::class, 'store'])->name('transaksi.store');
});
Route::post('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');