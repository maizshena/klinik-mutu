<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonsultasiTeknisController;
use App\Http\Controllers\PermohonanController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'))->name('home');

// guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // permohonan
    Route::resource('permohonan', PermohonanController::class);
    Route::post('permohonan/{permohonan}/assign', [PermohonanController::class, 'assign'])
        ->name('permohonan.assign');

    // konsultasi teknis
    Route::resource('konsultasi-teknis', KonsultasiTeknisController::class);
    Route::post('konsultasi-teknis/{konsultasiTeknis}/jawab', [KonsultasiTeknisController::class, 'jawab'])
        ->name('konsultasi-teknis.jawab');
});