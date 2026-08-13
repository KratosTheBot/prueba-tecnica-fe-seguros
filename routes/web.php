<?php

use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Route;


Route::get('/', [QueryController::class, 'index'])
    ->name('home');

Route::get('/search', [QueryController::class, 'search'])
    ->name('search');
