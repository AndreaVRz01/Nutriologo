<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\FormularioClinicoController;
use App\Http\Controllers\LandingController;
use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Middleware\EnsureNutriologo;
use Illuminate\Support\Facades\Route;

// Rutas Públicas
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Protegidas (Usando directamente las clases de middleware)
Route::middleware([EnsureAuthenticated::class])->group(function () {
    Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
    Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');

    // Exclusivo Nutriólogo
    Route::middleware([EnsureNutriologo::class])->group(function () {
        Route::post('/citas/masiva', [CitaController::class, 'cargaMasiva'])->name('citas.cargaMasiva');
        Route::get('/formularios', [FormularioClinicoController::class, 'index'])->name('formularios.index');
        Route::post('/formularios', [FormularioClinicoController::class, 'store'])->name('formularios.store');
        Route::post('/formularios/masiva', [FormularioClinicoController::class, 'cargaMasiva'])->name('formularios.cargaMasiva');
    });
});