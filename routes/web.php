<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Nasabah\DashboardController;
use App\Http\Controllers\Nasabah\TabunganController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Nasabah Routes (Frontend Only - No Auth Required)
Route::prefix('nasabah')->name('nasabah.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tabungan Routes
    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        Route::get('/', [TabunganController::class, 'index'])->name('index');
        Route::get('/nabung-sekarang', [TabunganController::class, 'nabungSekarang'])->name('nabung-sekarang');
        Route::get('/penarikan', [TabunganController::class, 'penarikanTabungan'])->name('penarikan');
    });
});
