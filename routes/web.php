<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeyController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/key/generate', [KeyController::class, 'generate']);
//Route::post('/api/v1/key/find', [KeyController::class, 'find']);

