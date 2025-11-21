<?php

use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\DescuentosController;
use App\Http\Controllers\DashboardIAController;
use App\Http\Controllers\JuegosController;   

// ============================
// DASHBOARD PRINCIPAL
// ============================
Route::get('/', [DashboardIAController::class, 'index'])
    ->name('dashboard');

// ============================
// JUEGOS
// ============================
Route::get('/juegos', [JuegosController::class, 'index'])
    ->name('juegos.index');

Route::get('/juegos/{id}', [JuegosController::class, 'show'])
    ->name('juegos.show');

// ============================
// DESCUENTOS (modelo antiguo)
// ============================
Route::get('/descuentos', [DescuentosController::class, 'index'])
    ->name('descuentos.index');

Route::post('/descuentos/consultar', [DescuentosController::class, 'consultar'])
    ->name('descuentos.consultar');

// ============================
// IA — CONSULTA A LOS 3 MODELOS
// ============================
Route::post('/dashboard/consultar-ia', [DashboardIAController::class, 'consultarIA'])
    ->name('dashboard.consultarIA');
