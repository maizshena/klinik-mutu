<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonsultasiTeknisController;
use App\Http\Controllers\KusukaClaimController;
use App\Http\Controllers\KusukaSearchController;
use App\Http\Controllers\PelakuUsahaFollowupController;
use App\Http\Controllers\PembinaanProaktifController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\SecureFileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'))->name('home');

// =========================
// guest (auth & registration)
// =========================
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // used by the register form to look up a business by its kusuka number
    Route::get('kusuka/search', [KusukaSearchController::class, 'search'])->name('kusuka.search');

    // todo(phase-4): replace these three stubs with real controllers
    // (forgot password email flow, admin invitation activation, petugas invitation activation)
    Route::get('forgot-password', fn () => Inertia::render('Auth/ForgotPassword'))->name('password.request');
    Route::get('aktivasi/admin', fn () => Inertia::render('Auth/ActivateAdmin'))->name('activation.admin');
    Route::get('aktivasi/petugas', fn () => Inertia::render('Auth/ActivatePetugas'))->name('activation.petugas');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // =========================
    // permohonan
    // =========================
    Route::resource('permohonan', PermohonanController::class);
    Route::post('permohonan/{permohonan}/assign', [PermohonanController::class, 'assign'])
        ->name('permohonan.assign');

    // =========================
    // konsultasi teknis
    // =========================
    Route::resource('konsultasi-teknis', KonsultasiTeknisController::class);
    Route::post('konsultasi-teknis/{konsultasiTeknis}/jawab', [KonsultasiTeknisController::class, 'jawab'])
        ->name('konsultasi-teknis.jawab');

    // =========================
    // pembinaan proaktif
    // =========================
    Route::resource('pembinaan-proaktif', PembinaanProaktifController::class)
        ->except(['edit', 'update', 'destroy']);
    Route::post('pembinaan-proaktif/{pembinaanProaktif}/assign', [PembinaanProaktifController::class, 'assign'])
        ->name('pembinaan-proaktif.assign');
    Route::post('pembinaan-proaktif/{pembinaanProaktif}/findings', [PembinaanProaktifController::class, 'submitFindings'])
        ->name('pembinaan-proaktif.submit-findings');
    Route::post('pembinaan-proaktif/{pembinaanProaktif}/complete', [PembinaanProaktifController::class, 'complete'])
        ->name('pembinaan-proaktif.complete');
    Route::post('pembinaan-proaktif/{pembinaanProaktif}/cancel', [PembinaanProaktifController::class, 'cancel'])
        ->name('pembinaan-proaktif.cancel');

    // =========================
    // tindak lanjut (followup)
    // =========================
    Route::resource('followup', PelakuUsahaFollowupController::class)
        ->except(['edit', 'update', 'destroy']);
    Route::post('followup/{followup}/progress', [PelakuUsahaFollowupController::class, 'updateProgress'])
        ->name('followup.update-progress');
    Route::post('followup/{followup}/evidence', [PelakuUsahaFollowupController::class, 'uploadEvidence'])
        ->name('followup.upload-evidence');
    Route::post('followup/{followup}/verify', [PelakuUsahaFollowupController::class, 'verify'])
        ->name('followup.verify');

    // =========================
    // kusuka claim (admin)
    // =========================
    Route::get('kusuka/claims', [KusukaClaimController::class, 'index'])->name('kusuka.claims.index');
    Route::post('kusuka/claims/{claim}/verify', [KusukaClaimController::class, 'verify'])->name('kusuka.claims.verify');

    // =========================
    // private files
    // =========================
    Route::get('files/konsultasi-teknis/{attachment}', [SecureFileController::class, 'konsultasiAttachment'])
        ->name('files.konsultasi-teknis');
});