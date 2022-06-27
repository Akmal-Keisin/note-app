<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    return redirect('/mynotes-admins');
});
// main
Route::middleware('auth-custom')->group(function () {
    Route::resource('/mynotes-admins', AdminController::class);
    // Route::resource('/mynotes-users',);
    Route::resource('/mynotes-users', UserController::class);
    Route::post('/auth/logout', [AuthController::class, 'authLogout']);
});

// Auth
Route::middleware('guest-custom')->group(function () {
    Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('/auth/login', [AuthController::class, 'authLogin']);
});
