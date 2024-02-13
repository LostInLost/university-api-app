<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth', [AuthController::class, 'auth']);

Route::prefix('admin')->group(function () {

    Route::post('/', [AdminController::class, 'store']);

    Route::prefix('dashboard')->middleware('auth:sanctum')->group(function() {
        Route::prefix('cities')->group(function () {
            Route::get('/', [CityController::class, 'index']);
            Route::get('/{id}', [CityController::class, 'detail']);
            Route::post('/', [CityController::class, 'store']);
            Route::put('/{id}', [CityController::class, 'edit']);
            Route::delete('/{id}', [CityController::class, 'destroy']);
        });
    
        Route::prefix('students')->group(function() {
            Route::get('/', [StudentController::class,'index']);
            Route::get('/{id}', [StudentController::class, 'detail']);
            Route::post('/', [StudentController::class, 'store']);
            Route::put('/{id}', [StudentController::class, 'edit']);
            Route::delete('/{id}', [StudentController::class, 'delete']);
        });
    });
    
});