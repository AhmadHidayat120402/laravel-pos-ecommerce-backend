<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ChatMessageController;

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



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// category
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/products/{id}', [ProductApiController::class, 'show']);
Route::put('/products/{id}', [ProductApiController::class, 'updateProduct']);
Route::delete('/products/{id}', [ProductApiController::class, 'destroyProduct']);

Route::apiResource('addresses', AddressController::class)->middleware('auth:sanctum');
Route::apiResource('products', ProductApiController::class)->middleware('auth:sanctum');

Route::post('/order', [OrderApiController::class, 'orderApi'])->middleware('auth:sanctum');
Route::post('/orderApiPos', [OrderApiController::class, 'orderApiPos'])->middleware('auth:sanctum');

// callback
Route::post('/callback', [CallbackController::class, 'callback']);

// check sttaus order by id order
Route::get('/order/status/{id}', [OrderApiController::class, 'checkStatusOrder'])->middleware('auth:sanctum');

Route::get('/orders', [OrderApiController::class, 'getOrderByUser'])->middleware('auth:sanctum');

//get order by id
Route::get('/order/{id}', [OrderApiController::class, 'getOrderById'])->middleware('auth:sanctum');

//discounts api
Route::get('/api-discounts', [DiscountController::class, 'index'])->middleware('auth:sanctum');

Route::post('/api-discounts', [DiscountController::class, 'store'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('send-message', [MessageController::class, 'sendMessage']);
Route::middleware('auth:sanctum')->get('messages/{channel_id}', [MessageController::class, 'getMessages']);

Route::middleware('auth:sanctum')->post('channels', [ChannelController::class, 'create']);
Route::middleware('auth:sanctum')->get('channels', [ChannelController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index', 'store']);
    Route::apiResource('userApi', UserApiController::class)->only(['index']);
});
