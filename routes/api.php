<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TagihanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('dashboard',DashboardController::class);
Route::get('/tagihan', [TagihanController::class, 'index']);
Route::post('/tagihan/generate', [TagihanController::class, 'generate']);
Route::put('/tagihan/{id}', [TagihanController::class, 'update']);