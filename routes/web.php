<?php

use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\DescuentosController;
use App\Http\Controllers\DashboardIAController;   

// ============================
// DASHBOARD PRINCIPAL
// ============================
Route::get('/', [DashboardIAController::class, 'index'])
    ->name('dashboard');

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
