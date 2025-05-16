<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeyController;

Route::prefix('v1')->group(function () {
    Route::post('/key/generate', [KeyController::class, 'generate']);
    Route::post('/key/get_key', [KeyController::class, 'get_key']);
});
