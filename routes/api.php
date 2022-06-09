<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserApiController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/mynote', NoteController::class)->except(['create', 'edit']);
    Route::post('/auth/logout', [AuthApiController::class, 'authLogout']);
    Route::get('/user-profile', [UserApiController::class, 'show']);
    Route::put('/user-profile', [UserApiController::class, 'update']);
});

Route::get('/', function () {
    return redirect('/note');
});

Route::post('/auth/register', [AuthApiController::class, 'authRegister']);
Route::post('/auth/login', [AuthApiController::class, 'authLogin']);
