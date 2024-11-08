<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('get_data')->group(function () {
    Route::get('/', [DataController::class, 'index']);
    Route::get('/show', [DataController::class, 'show']);
});

Route::prefix('/')->group(function () {
    Route::get('/', [DataController::class, 'create']);
    Route::get('/show', [DataController::class, 'show']);
    Route::get('/data', [DataController::class, 'data']);
    Route::post('/verify', [DataController::class, 'verify']);
    Route::post('/uploads', [DataController::class, 'uploads']);
});
