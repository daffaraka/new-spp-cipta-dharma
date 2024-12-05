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
use App\Http\Controllers\LaporanSiswaController;
use App\Http\Controllers\LaporanSPPController;
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

    Route::resource('siswa', SiswaController::class);
    Route::get('export-siswa',[SiswaController::class,'export'])->name('siswa.export');
    Route::get('print-siswa',[SiswaController::class,'print'])->name('siswa.print');
    Route::post('import',[SiswaController::class,'import'])->name('siswa.import');
    Route::post('filter-siswa', [SiswaController::class, 'filter'])->name('siswa.filter');

    Route::resource('tagihan', TagihanController::class);
    Route::post('filter-tagihan', [TagihanController::class, 'filter'])->name('tagihan.filter');

    Route::resource('biaya', BiayaController::class);
    Route::post('filter-biaya', [BiayaController::class, 'filter'])->name('biaya.filter');

    Route::resource('pembayaran', PembayaranController::class);
    Route::get('pembayaran/verifikasi/{id}', [PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    Route::post('pembayaran/kuitansi/{id}', [PembayaranController::class, 'kirimKuitansi'])->name('pembayaran.kuitansi');

    Route::post('filter-pembayaran', [BiayaController::class, 'filter'])->name('pembayaran.filter');

    Route::get('laporan-petugas', [LaporanPetugasController::class, 'index'])->name('laporanPetugas.index');
    Route::get('laporan-petugas/create', [LaporanPetugasController::class, 'create'])->name('laporanPetugas.create');
    Route::get('laporan-petugas/{petugas}', [LaporanPetugasController::class, 'show'])->name('laporanPetugas.show');
    Route::post('filter-laporan-petugas', [LaporanPetugasController::class, 'filter'])->name('laporanPetugas.filter');


    Route::get('laporan-siswa', [LaporanSiswaController::class, 'index'])->name('laporanSiswa.index');
    Route::get('laporan-siswa/create', [laporanSiswaController::class, 'create'])->name('laporanSiswa.create');
    Route::get('laporan-siswa/{siswa}', [laporanSiswaController::class, 'show'])->name('laporanSiswa.show');
    Route::post('filter-laporan-siswa', [laporanSiswaController::class, 'filter'])->name('laporanSiswa.filter');


    Route::get('laporan-spp', [LaporanSPPController::class, 'index'])->name('laporanSpp.index');
    Route::get('laporan-spp/create', [LaporanSPPController::class, 'create'])->name('laporanSpp.create');
    Route::get('laporan-spp/{spp}', [LaporanSPPController::class, 'show'])->name('laporanSpp.show');
    Route::post('filter-laporan-spp', [LaporanSPPController::class, 'filter'])->name('laporanSpp.filter');



    Route::resource('petugas', PetugasController::class)->parameters([
        'petugas' => 'petugas'
    ]);


    Route::get('pelunasan/{id}', [PelunasanController::class, 'tagihan'])->name('pelunasan.tagihan');
    Route::post('lunasi/{id}', [PelunasanController::class, 'lunasi'])->name('pelunasan.lunasi');
    Route::view('print', 'admin.tagihan.tagihan-print');
});


require __DIR__ . '/auth.php';
