<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Models\Reseller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::middleware(['auth'])->group(function () {
    // Rute yang hanya dapat diakses oleh role 1
    Route::group(['middleware' => function ($request, $next) {
        if (Auth::check() && Auth::user()->role == 1) {
            return $next($request);
        }
        return redirect('/')->with('error', 'Tidak memiliki akses.');
    }], function () {
        Route::resource('users', UserController::class);
        Route::get('laporan', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/export-pembayaran', [PembayaranController::class, 'exportExcel'])->name('export.pembayaran');
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::post('/transaksi/{reseller}', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::post('/invoice/pdf/{id}', [InvoiceController::class, 'generateInvoice'])->name('invoice.generate');
    });

    // Rute yang dapat diakses oleh role 1 dan role 2
    Route::group(['middleware' => function ($request, $next) {
        if (Auth::check() && (Auth::user()->role == 1 || Auth::user()->role == 2)) {
            return $next($request);
        }
        return redirect('/')->with('error', 'Tidak memiliki akses.');
    }], function () {
        Route::get('/home', [ResellerController::class, 'index']);
        Route::resource('reseller', ResellerController::class);
        Route::post('/reseller/toggle-status/{id}', [ResellerController::class, 'toggleStatus'])->name('reseller.toggleStatus');
    });
});


Route::post('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');