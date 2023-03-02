<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AplicacionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/aplicacion/getAplicacionesFull', [AplicacionController::class, 'getAplicacionesFull']);
Route::post('/aplicacion/getAplicaciones', [AplicacionController::class, 'getAplicaciones']);
Route::post('/aplicacion/crearAplicaciones', [AplicacionController::class, 'crearAplicaciones']);
Route::post('/aplicacion/actualizarAplicaciones', [AplicacionController::class, 'actualizarAplicaciones']);
Route::get('/aplicacion/getTipoAplicaciones', [AplicacionController::class, 'getTipoAplicaciones']);

