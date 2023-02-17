<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout']);

Route::get('students', [StudentController::class, 'index']);
Route::post('/add-student', [StudentController::class, 'store']);
Route::get('/edit-student/{id}', [StudentController::class, 'edit']);
Route::put('update-student/{id}', [StudentController::class, 'update']);
Route::delete('delete-student/{id}', [StudentController::class, 'destroy']);
Route::get('/view-student/{id}', [StudentController::class, 'show']);

Route::get('products', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'singleProduct']);
Route::post('add-to-cart', [ProductController::class, 'addToCart']);
Route::put('cart-update/{id}', [ProductController::class, 'cartUpdate']);
Route::delete('cart-delete/{id}', [ProductController::class, 'cartDelete']);
Route::post('cart-list', [ProductController::class, 'cartList']);
Route::get('category', [ProductController::class, 'getCategory']);
Route::post('product-search', [ProductController::class, 'productSearch']);