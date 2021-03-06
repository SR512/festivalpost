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

Route::resource('categories', \App\Http\Controllers\CategoryController::class);
Route::get('search/categories/{field}/{query}', [App\Http\Controllers\CategoryController::class, 'search']);
Route::get('status/categories/{id}', [App\Http\Controllers\CategoryController::class, 'changeStatus']);


Route::resource('posts', \App\Http\Controllers\PostController::class);
Route::get('status/posts/{id}', [App\Http\Controllers\PostController::class, 'changeStatus']);

Route::resource('festivals', \App\Http\Controllers\FestivalController::class);

Route::get('getcustomcategorypost', [App\Http\Controllers\FestivalController::class, 'getcustomcategorypost']);
Route::get('getCategory', [App\Http\Controllers\FestivalController::class, 'getCategory']);
Route::get('getFestival', [App\Http\Controllers\FestivalController::class, 'getFestival']);

Route::get('search/festivals/{field}/{query}', [App\Http\Controllers\FestivalController::class, 'search']);
Route::get('status/festivals/{id}', [App\Http\Controllers\FestivalController::class, 'changeStatus']);

Route::resource('images', \App\Http\Controllers\ImageController::class);


Route::resource('customcategories', \App\Http\Controllers\CustomCategoryController::class);
Route::get('search/customcategories/{field}/{query}', [App\Http\Controllers\CustomCategoryController::class, 'search']);
Route::get('status/customcategories/{id}', [App\Http\Controllers\CustomCategoryController::class, 'changeStatus']);

Route::resource('customimages', \App\Http\Controllers\CustomImageController::class);
