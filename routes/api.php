<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\productcontroller;
use App\Http\Controllers\SubcategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

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
//Auth Api Route 
Route::controller(Authcontroller::class)->group(function () {
    Route::post('store', 'store');
    Route::post('/login', 'login')->name('login');
    Route::post('/forgotPasswordLink', 'forgotPasswordLink');
    Route::post('/forgotPassword', 'forgotPassword');
    Route::get('/list', 'index');   
});

//User Api Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('/show/{id}', 'show');
        Route::put('/update/{id}', 'update');
        Route::delete('delete/{id}', 'delete');
        Route::post('/logout', 'logout');
        Route::post('/changepassword', 'changepassword');
    });
    //Category Api Route
    Route::controller(CategoryController::class)->prefix('category')->group(function () {
        Route::post('/store', 'store')->middleware('role:admin');
        Route::get('/show/{id}', 'show')->middleware('role:admin|user');
        Route::put('/update/{id}', 'update')->middleware('role:admin');
        Route::delete('/delete/{id}', 'delete')->middleware('role:admin');
        Route::get('/list', 'index')->middleware('role:admin|user');
    });
    //SubCategory Api Route 
    Route::controller(SubcategoryController::class)->prefix('subcategory')->group(function () {
        Route::post('/store', 'store')->middleware('role:admin');
        Route::get('/show/{id}', 'show')->middleware('role:admin|user');
        Route::put('/update/{id}', 'update')->middleware('role:admin');
        Route::delete('/delete/{id}', 'delete')->middleware('role:admin');
        Route::get('/list', 'index')->middleware('role:admin|user');
    });
    //Product Api Route
    Route::controller(ProductController::class)->prefix('product')->group(function () {
        Route::post('/store', 'store')->middleware('role:admin');
        Route::get('/show/{id}', 'show')->middleware('role:admin|user');
        Route::put('/update/{id}', 'update')->middleware('role:admin');
        Route::delete('/delete/{id}', 'delete')->middleware('role:admin');
        Route::get('/list', 'index')->middleware('role:admin|user');
    });
    //Order Api Route
    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::post('/store', 'store')->middleware('role:user');
        Route::get('/show/{id}', 'show')->middleware('role:admin|user');
        Route::put('/update/{id}', 'update')->middleware('role:admin');
        Route::delete('/delete/{id}', 'delete')->middleware('role:admin');
        Route::get('/list', 'index')->middleware('role:admin|user');
    });
    //Order Api Route
    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::post('/store', 'store')->middleware('role:admin');
        Route::get('/show/{id}', 'show')->middleware('role:admin|user');
        Route::put('/update/{id}', 'update')->middleware('role:admin');
        Route::delete('/delete/{id}', 'delete')->middleware('role:admin');
        Route::get('/list', 'index')->middleware('role:admin|user');
    });
});