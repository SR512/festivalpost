<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('categories',\App\Http\Controllers\CategoryController::class);
Route::get('search/categories/{field}/{query}',[App\Http\Controllers\CategoryController::class, 'search']);
Route::get('status/categories/{id}',[App\Http\Controllers\CategoryController::class, 'changeStatus']);



Route::resource('posts',\App\Http\Controllers\PostController::class);


Route::resource('festivals',\App\Http\Controllers\FestivalController::class);
Route::get('festivallist',[App\Http\Controllers\FestivalController::class, 'getFestival']);
Route::get('search/festivals/{field}/{query}',[App\Http\Controllers\FestivalController::class, 'search']);
Route::get('status/festivals/{id}',[App\Http\Controllers\FestivalController::class, 'changeStatus']);

Route::resource('images',\App\Http\Controllers\ImageController::class);
