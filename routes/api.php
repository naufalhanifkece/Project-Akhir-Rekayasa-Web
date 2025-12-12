<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Routes untuk CRUD API Sekolah
|
*/

// Routes untuk Siswa
Route::prefix('siswa')->group(function () {
    Route::post('/create', [SiswaController::class, 'store'])->name('siswa.create');
    Route::get('/read', [SiswaController::class, 'index'])->name('siswa.read');
    Route::put('/update/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/delete/{id}', [SiswaController::class, 'destroy'])->name('siswa.delete');
});

// Routes untuk Guru
Route::prefix('guru')->group(function () {
    Route::post('/create', [GuruController::class, 'store'])->name('guru.create');
    Route::get('/read', [GuruController::class, 'index'])->name('guru.read');
    Route::put('/update/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/delete/{id}', [GuruController::class, 'destroy'])->name('guru.delete');
});

// Routes untuk Kelas
Route::prefix('kelas')->group(function () {
    Route::post('/create', [KelasController::class, 'store'])->name('kelas.create');
    Route::get('/read', [KelasController::class, 'index'])->name('kelas.read');
    Route::put('/update/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/delete/{id}', [KelasController::class, 'destroy'])->name('kelas.delete');
});
