<?php

use App\Http\Controllers\API\v1\authController;
use App\Http\Controllers\API\v1\JWTAuthController as JWTController;
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

Route::group(['middleware' => 'api','prefix' => 'auth'], function () {
    Route::post('/register', ['as' => 'register', JWTController::class, 'register']);
    Route::post('/login', ['as' => 'login', JWTController::class, 'login']);
    Route::get('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
});

// Route::post('/login', [authController::class, 'login']);
// Route::post('/register', [authController::class, 'register']);
// Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::get('/logout', [authController::class, 'logout']);
// });
