<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\PlantationController;
use \App\Http\Controllers\Api\ActivityController;
use \App\Http\Controllers\Api\HistoryController;
use \App\Http\Controllers\Api\AnalisysController;
use App\Helpers\Helpers;

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
Route::middleware('auth:sanctum')->delete('/auth/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'object' => Helpers::convertToCamelCase($request->user()->toArray()),
    ], 200);
});

Route::prefix('plantations')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PlantationController::class, 'index']);
    Route::middleware('owner')->post('/', [PlantationController::class, 'store']);
    Route::get('/{id}', [PlantationController::class, 'show']);
    Route::get('/{id}/associates', [PlantationController::class, 'associates']);
    Route::middleware('owner')->put('/{id}', [PlantationController::class, 'update']);
    Route::middleware('owner')->delete('/{id}', [PlantationController::class, 'destroy']);
});

Route::prefix('activities')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ActivityController::class, 'index']);
    Route::middleware('owner')->post('/', [ActivityController::class, 'store']);
    Route::get('/{id}', [ActivityController::class, 'show']);
    Route::middleware('owner')->put('/{id}', [ActivityController::class, 'update']);
    Route::middleware('owner')->delete('/{id}', [ActivityController::class, 'destroy']);
    Route::patch('/{id}/finish', [ActivityController::class, 'finish']);

    // * Histories
    Route::get('/{id}/histories', [HistoryController::class, 'index']);
    Route::post('/{id}/histories', [HistoryController::class, 'store']);
    Route::get('/{activity}/histories/{id}', [HistoryController::class, 'show']);
});

Route::prefix('analysis')->middleware('auth:sanctum')->group(function () {
    Route::get('agricultural-input-expenses/{id}', [AnalisysController::class, 'agriculturalInputExpenses']);
    Route::get('late-activities/{id}', [AnalisysController::class, 'lateActivities']);
});
