<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\AuthController;

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


Route::post('/signup', [UserController::class, 'userRegister'])->name('user.register');
Route::post('/login', [UserController::class, 'login'])->name('user.signin');
Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');

// Route::middleware(['auth'])->group(function () {
//     Route::post('/createtype', [TypeController::class, 'createTypeForUser'])->name('user.createtype');
//     Route::get('/gettype/{id}', [TypeController::class, 'getType'])->name('user.gettype');

// });
Route::post('/createtype', [TypeController::class, 'createTypeForUser'])->name('user.createtype');
Route::get('/gettype/{id}', [TypeController::class, 'getType'])->name('user.gettype');

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
// Route::get('/debug', function () {
//     return response()->json(['status' => 'API 跑得起來']);
// });

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);