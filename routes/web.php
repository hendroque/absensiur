<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\KonfigurasiController;
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

Route::middleware(['guest:pegawai'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});


Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});


Route::middleware(['auth:pegawai'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);


    //presensi
    Route::get('presensi/create', [PresensiController::class, 'create']);


    //Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{nip}/updateprofile', [PresensiController::class, 'updateprofile']);

    //History
    Route::get('/presensi/history', [PresensiController::class, 'history']);
    Route::post('/gethistory', [PresensiController::class, 'gethistory']);

    //Izin
    Route::get('presensi/izin', [PresensiController::class, 'izin']);
    Route::get('presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('presensi/storeizin', [PresensiController::class, 'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);
});

Route::middleware(['auth:user'])->group(function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);
    Route::post('/validasi/qrcode', [DashboardController::class, 'validasiQrcode'])->name('validasiqrcode');

    //Karyawan
    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai/store', [PegawaiController::class, 'store']);
    Route::post('/pegawai/edit', [PegawaiController::class, 'edit']);
    Route::post('/pegawai/{nip}/update', [PegawaiController::class, 'update']);
    Route::post('/pegawai/{nip}/delete', [PegawaiController::class, 'delete']);

    //Departement
    Route::get('/bagian', [BagianController::class, 'index']);
    Route::post('/bagian/store', [BagianController::class, 'store']);
    Route::post('/bagian/edit', [BagianController::class, 'edit']);
    Route::post('/bagian/{kode_bag}/update', [BagianController::class, 'update']);
    Route::post('/bagian/{kode_bag}/delete', [BagianController::class, 'delete']);


    //Presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getabsensi', [PresensiController::class, 'getabsensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::post('/presensi/cetakrekapbulan', [PresensiController::class, 'cetakrekapbulan']);

    //izinsakit
    Route::get('presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);
    //konfigurasi
    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
    Route::post('/konfigurasi/storejamkerja', [KonfigurasiController::class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja', [KonfigurasiController::class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);
    Route::post('/konfigurasi/jamkerja/{kode_jam_kerja}/delete', [KonfigurasiController::class, 'deletejamkerja']);
    Route::get('/konfigurasi/{nip}/setjamkerja', [KonfigurasiController::class, 'setjamkerja']);
    Route::post('/konfigurasi/storesetjamkerja', [KonfigurasiController::class, 'storesetjamkerja']);
    Route::post('/konfigurasi/updatesetjamkerja', [KonfigurasiController::class, 'updatesetjamkerja']);

    Route::get('/konfigurasi/jamkerjabag', [KonfigurasiController::class, 'jamkerjabag']);
    Route::get('/konfigurasi/jamkerjabag/create', [KonfigurasiController::class, 'createjamkerjabag']);
    Route::post('/konfigurasi/jamkerjabag/store', [KonfigurasiController::class, 'storejamkerjabag']);
    Route::get('/konfigurasi/jamkerjabag/{kode_jk_dept}/edit', [KonfigurasiController::class, 'editjamkerjabag']);
    Route::post('/konfigurasi/jamkerjabag/{kode_jk_dept}/update', [KonfigurasiController::class, 'updatejamkerjabag']);
    Route::get('/konfigurasi/jamkerjabag/{kode_jk_dept}/show', [KonfigurasiController::class, 'showjamkerjabag']);
    Route::get('/konfigurasi/jamkerjabag/{kode_jk_dept}/delete', [KonfigurasiController::class, 'deletejamkerjabag']);
});
