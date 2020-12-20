<?php

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

Route::post('register', [\App\Http\Controllers\ApiController::class,'register']);
Route::post('savebusiness', [\App\Http\Controllers\ApiController::class,'savebusiness']);
Route::post('login', [\App\Http\Controllers\ApiController::class,'login']);
Route::post('logout', [\App\Http\Controllers\ApiController::class,'logout']);
Route::post('resendotp', [\App\Http\Controllers\ApiController::class,'resendotp']);
Route::post('getHomepage', [\App\Http\Controllers\ApiController::class,'getHomepage']);
Route::post('getImage', [\App\Http\Controllers\ApiController::class,'getImage']);
Route::post('getPackage', [\App\Http\Controllers\ApiController::class,'getPackage']);

