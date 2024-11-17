<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelunasanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanPetugasController;
use App\Models\Siswa;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', function () {
        return to_route('dashboard');
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('siswa',SiswaController::class);
    Route::post('filter-siswa',[SiswaController::class,'filter'])->name('siswa.filter');

    Route::resource('tagihan',TagihanController::class);
    Route::post('filter-tagihan',[TagihanController::class,'filter'])->name('tagihan.filter');

    Route::resource('biaya',BiayaController::class);
    Route::post('filter-biaya',[BiayaController::class,'filter'])->name('biaya.filter');

    Route::resource('pembayaran',PembayaranController::class);
    Route::post('filter-pembayaran',[BiayaController::class,'filter'])->name('pembayaran.filter');

    Route::resource('laporan-petugas',LaporanPetugasController::class);
    Route::resource('petugas',LaporanPetugasController::class);


    Route::get('pelunasan/{id}', [PelunasanController::class, 'tagihan'])->name('pelunasan.tagihan');
    Route::post('lunasi/{id}',[PelunasanController::class,'lunasi'])->name('pelunasan.lunasi');
    Route::view('print','admin.tagihan.tagihan-print');

});


require __DIR__.'/auth.php';


