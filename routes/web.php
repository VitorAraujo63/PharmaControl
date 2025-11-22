<?php

use App\Http\Controllers\CupomController;
use App\Http\Controllers\EmployeeAuthController;
use App\Http\Middleware\CheckUserStatus;
use App\Livewire\AuditLogs;
use App\Livewire\CreateProduct;
use App\Livewire\CreateSale;
use App\Livewire\Dashboard;
use App\Livewire\EditProduct;
use App\Livewire\FinancialReport;
use App\Livewire\ListProducts;
use App\Livewire\ManageUsers;
use App\Livewire\SalesHistory;
use App\Livewire\StockEntry;
use App\Livewire\TwoFactorChallenge;
use App\Livewire\TwoFactorManage;
use App\Livewire\UserProfile;
use Illuminate\Support\Facades\Route;

// --- ROTAS PÚBLICAS (LOGIN) ---
Route::get('/login', [EmployeeAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [EmployeeAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [EmployeeAuthController::class, 'logout'])->name('logout');

// --- ROTAS PROTEGIDAS (DASHBOARD) ---
Route::middleware(['auth', CheckUserStatus::class, '2fa'])->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/venda', CreateSale::class)->name('venda');
    Route::get('/perfil', UserProfile::class)->name('user.profile');

    Route::middleware(['can:manager-access'])->group(function () {
        Route::get('/produtos', ListProducts::class)->name('produtos.index');
        Route::get('/produtos/novo', CreateProduct::class)->name('produtos.novo');
        Route::get('/produtos/{product}/editar', EditProduct::class)->name('produtos.editar');
        Route::get('/estoque/entrada', StockEntry::class)->name('estoque.entrada');
        Route::get('/vendas/historico', SalesHistory::class)->name('vendas.historico');
        Route::get('/venda/{id}/cupom', [CupomController::class, 'imprimir'])->name('venda.cupom');
        Route::get('/relatorios/financeiro', FinancialReport::class)->name('relatorios.financeiro');
    });


    Route::middleware(['can:admin-access'])->group(function () {
        Route::get('/auditoria', AuditLogs::class)->name('auditoria');
        Route::get('/usuarios', ManageUsers::class)->name('usuarios.index');
    });

});

Route::middleware(['auth', CheckUserStatus::class])->group(function () {


    Route::get('/seguranca/2fa', TwoFactorManage::class)
        ->middleware('2fa')
        ->name('2fa.manage');

    // Rota do Desafio (Onde o Middleware joga quem precisa validar)
    Route::get('/login/2fa', TwoFactorChallenge::class)->name('2fa.verify');

});


