<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenBimbinganController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── GLOBAL CHAT ──
Route::post('/chat', [ChatController::class, 'chat']);

// ── SENSOR PIR ──
Route::post('/api/sensor/pir', [SensorController::class, 'storeData']);
Route::post('/sensor/pir', [SensorController::class, 'storeData']);
Route::get('/api/dosen/status', [DosenController::class, 'statusApi']);

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'dosen'
            ? redirect()->route('dosen.dashboard')
            : redirect()->route('mahasiswa.dashboard');
    }
    return redirect()->route('login');
});

// ── GUEST ONLY (BELUM LOGIN) ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/select-role', function() {
        return view('auth.select-role');
    })->name('register.role');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

    

// ── AUTH REQUIRED (WAJIB LOGIN) ──
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return Auth::user()->role === 'dosen'
            ? redirect()->route('dosen.dashboard')
            : redirect()->route('mahasiswa.dashboard');
    })->name('dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/password', [AuthController::class, 'editPassword'])
            ->name('password.edit');

    Route::put('/password', [AuthController::class, 'updatePassword'])
        ->name('password.update');

    // ── GRUP DOSEN (Prefix otomatis agar rapi dan bebas 404) ──
    Route::prefix('dosen')->name('dosen.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
        
        // Pengaturan Profil (Placeholder sementara)
        Route::get('/profil', function() {
            return "Halaman Profil Dosen (Dalam Pengembangan)";
        })->name('profil');

        // ================= JADWAL =================
        Route::get('/jadwal', [DosenController::class, 'jadwal'])->name('jadwal');
        Route::post('/jadwal', [DosenController::class, 'storeJadwal'])->name('storeJadwal');
        Route::delete('/jadwal/{id}', [DosenController::class, 'destroyJadwal'])->name('destroyJadwal');


        // ================= BIMBINGAN =================
        Route::get('/bimbingan', [DosenController::class, 'bimbingan'])->name('bimbingan');
        Route::post('/bimbingan/{id}/approve', [DosenController::class, 'approve'])->name('bimbingan.approve');
        Route::post('/bimbingan/{id}/reject', [DosenController::class, 'reject'])->name('bimbingan.reject');

        // ================= STATUS =================
        Route::put('/status', [DosenController::class, 'updateStatus'])
            ->name('status.update');


        // ================= PROFIL =================
        Route::put('/profil', [DosenController::class, 'updateProfil'])
            ->name('profil.update');

        }); 

    // ── GRUP MAHASISWA ──
   Route::prefix('mahasiswa')->group(function () {

    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])
        ->name('mahasiswa.dashboard');

    Route::get('/jadwal/{id}', [MahasiswaController::class, 'getJadwal'])
        ->name('mahasiswa.jadwal');

    Route::resource('/bimbingan', BimbinganController::class)->names([
        'index'   => 'mahasiswa.bimbingan',
        'create'  => 'mahasiswa.bimbingan.create',
        'store'   => 'mahasiswa.bimbingan.store',
        'show'    => 'mahasiswa.bimbingan.show',
        'edit'    => 'mahasiswa.bimbingan.edit',
        'update'  => 'mahasiswa.bimbingan.update',
        'destroy' => 'mahasiswa.bimbingan.destroy',
    ]);

});


});

require __DIR__.'/auth.php';