<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\PelangganAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganDashboardController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenggunaanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;   // Import AdminMiddleware
use App\Http\Middleware\PetugasMiddleware; // Import PetugasMiddleware
use Illuminate\Support\Facades\Route;


// Route untuk login tunggal Admin
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
// Grup Route untuk Admin
Route::middleware(['auth:web', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
 // Ubah rute dashboard Admin untuk menunjuk ke DashboardController
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Rute-rute untuk Manajemen  (Admin bisa CRUD)
    Route::resource('tarifs', TarifController::class);
    // Rute-rute untuk Manajemen pelanggan (Admin bisa CRUD)
    Route::resource('pelanggans', PelangganController::class);
    // Rute-rute untuk Manajemen Penggunaan (Admin bisa CRUD)
    Route::resource('penggunaans', PenggunaanController::class); 
     // Rute-rute untuk Manajemen Tagihan
    Route::get('tagihans/generate', [TagihanController::class, 'createFromPenggunaan'])->name('tagihans.create_from_penggunaan');
    Route::post('tagihans/generate', [TagihanController::class, 'generate'])->name('tagihans.generate');
    Route::resource('tagihans', TagihanController::class);
    
    // Rute-rute untuk Manajemen Pembayaran (Admin bisa CRUD)
    Route::resource('pembayarans', PembayaranController::class);    
    
     // Rute-rute untuk Manajemen User (Hanya Admin)
    Route::resource('users', UserController::class); 
});

// Grup Route untuk Petugas
Route::middleware(['auth:web', PetugasMiddleware::class])->prefix('petugas')->name('petugas.')->group(function () {
    // Ubah rute dashboard Admin untuk menunjuk ke DashboardController
    Route::get('/dashboard', [DashboardController::class, 'petugasDashboard'])->name('dashboard');

    // Rute-rute untuk Manajemen Tarif (Petugas hanya bisa Read: index, show)
    Route::resource('tarifs', TarifController::class)->only(['index', 'show']);
    // Rute-rute untuk Manajemen Penggunaan (Petugas bisa CRUD)
    Route::resource('penggunaans', PenggunaanController::class);
    // Rute-rute untuk Manajemen Pelanggan (Petugas hanya bisa Read: index, show)
    Route::resource('pelanggans', PelangganController::class); 
   
    // Rute-rute untuk Manajemen Tagihan
    Route::get('tagihans/generate', [TagihanController::class, 'createFromPenggunaan'])->name('tagihans.create_from_penggunaan');
    Route::post('tagihans/generate', [TagihanController::class, 'generate'])->name('tagihans.generate');
    Route::resource('tagihans', TagihanController::class);
    
    // Rute-rute untuk Manajemen Pembayaran (Admin bisa CRUD)
    Route::resource('pembayarans', PembayaranController::class); 

});

// Grup Route untuk Pelanggan
Route::middleware(['auth:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {

    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat-penggunaan', [PelangganDashboardController::class, 'riwayatPenggunaan'])->name('riwayat_penggunaan');
    Route::get('/tagihan-saya', [PelangganDashboardController::class, 'tagihanSaya'])->name('tagihan_saya');
// Rute untuk Profil Saya
    Route::get('/profil', [PelangganDashboardController::class, 'profilSaya'])->name('profil_saya'); // Tampilkan form
    Route::put('/profil', [PelangganDashboardController::class, 'updateProfil'])->name('update_profil'); // Proses update
     // Rute baru untuk Riwayat Pembayaran
     Route::post('/tagihan/{id}/bayar', [PelangganDashboardController::class, 'bayarTagihan'])
     ->name('bayar_tagihan');
    Route::get('/riwayat-pembayaran', [PelangganDashboardController::class, 'riwayatPembayaran'])->name('riwayat_pembayaran');
});


// Halaman utama akan langsung redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/lupa-password', function () {
    return view('auth.lupa-password');
});







