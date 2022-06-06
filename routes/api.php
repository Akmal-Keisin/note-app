<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\NoteController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return redirect('/note');
});

Route::post('/auth/register', [AuthApiController::class, 'authRegister']);
Route::post('/auth/login', [AuthApiController::class, 'authLogin']);
Route::resource('/note', NoteController::class)->except(['create', 'edit']);
