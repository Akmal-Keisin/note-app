<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::resource('/mynotes-admins', AdminController::class);
Route::resource('/mynotes-users', UserController::class);

// Auth
Route::get('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'authLogin']);
Route::post('/auth/register', [AuthController::class, 'authRegister']);
Route::get('/test', function () {
    return view('tables');
});
