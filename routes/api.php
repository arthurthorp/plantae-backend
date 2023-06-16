<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\PlantationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('plantation')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PlantationController::class, 'index']);
    Route::middleware('owner')->post('/', [PlantationController::class, 'store']);
    Route::get('/{id}', [PlantationController::class, 'show']);
    Route::middleware('owner')->put('/{id}', [PlantationController::class, 'update']);
    Route::middleware('owner')->delete('/{id}', [PlantationController::class, 'destroy']);
});
