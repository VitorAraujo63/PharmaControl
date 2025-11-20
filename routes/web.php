<?php

use App\Livewire\CreateSale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/venda', CreateSale::class);
