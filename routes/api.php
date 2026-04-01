<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\EmployerController;

// "token": "1|r0WnBKJBekMorkw2KrGNWhWXuV6fM9q4ckGWTja7f1395314" - Токен работодателя
// "token": "2|6IACSejWZRGz0k6gpy04gkDidD72q0Mf6xwG4s4N491c2e1e" - токен соискателя

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('vacancies', [VacancyController::class, 'index']);
    Route::get('vacancies/{id}', [VacancyController::class, 'show']);
    Route::prefix('responses')->group(function () {
        Route::get('/', [ResponseController::class, 'index']);
        Route::get('{id}', [ResponseController::class, 'show']);
        Route::put('{id}', [ResponseController::class, 'update']);
        Route::delete('{id}', [ResponseController::class, 'destroy']);
    });
    Route::post('vacancies/{id}/responses', [ResponseController::class, 'store']);
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('{id}', [FavoriteController::class, 'store']);
        Route::delete('{id}', [FavoriteController::class, 'destroy']);
        Route::post('{id}/responses', [ResponseController::class, 'storeFromFavorite']);
    });
    Route::prefix('employer')->middleware('role:employer')->group(function () {
        Route::prefix('vacancies')->group(function () {
            Route::get('/', [EmployerController::class, 'index']);
            Route::post('/', [EmployerController::class, 'store']);
            Route::get('{id}', [EmployerController::class, 'show']);
            Route::put('{id}', [EmployerController::class, 'update']);
            Route::delete('{id}', [EmployerController::class, 'destroy']);
        });
        Route::get('responses', [EmployerController::class, 'responses']);
        Route::put('responses/{id}/status', [EmployerController::class, 'updateResponseStatus']);
    });
});

