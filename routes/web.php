<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::prefix('admin')->group(function () {
    // dashborad
    Route::get('/', [DashboardController::class, 'index']);

    // user
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/saveUser', [UserController::class, 'store']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);

    // product
    Route::get('/product', [ProductController::class, 'index']);
    Route::post('/saveProduct', [ProductController::class, 'store']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
