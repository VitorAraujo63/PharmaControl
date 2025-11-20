<?php

use App\Livewire\CreateSale;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class);

Route::get('/venda', CreateSale::class);
