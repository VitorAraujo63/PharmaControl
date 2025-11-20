<?php

use App\Http\Controllers\EmployeeAuthController;
use App\Livewire\CreateSale;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

// --- ROTAS PÚBLICAS (LOGIN) ---
Route::get('/login', [EmployeeAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [EmployeeAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [EmployeeAuthController::class, 'logout'])->name('logout');

// --- ROTAS PROTEGIDAS (DASHBOARD) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/venda', CreateSale::class)->name('venda');
});
