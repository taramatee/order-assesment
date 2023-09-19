<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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


Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('validate-login', [AuthController::class, 'validateLogin'])->name('login.validate');

Route::group(['middleware' => ['auth']], function() {
    Route::get('orders', [AuthController::class, 'dashboard']);
    Route::post('get-product', [AuthController::class, 'getProduct'])->name('get-product');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
