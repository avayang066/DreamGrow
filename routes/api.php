<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\TrackableItemController;
use App\Http\Controllers\TrackLogsController;

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

// 會員 & User
Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'userRegister')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::middleware('auth:sanctum')->post('/logout', 'logout')->name('logout');
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    })->name('user.profile');
});

// 種類頁面
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('type', TypeController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']); // type
    Route::apiResource('type.trackable-item', TrackableItemController::class) // /api/type/{type}/trackable-item/{trackable-item}
        ->only(['index', 'store', 'update', 'destroy', 'show']); // trackable-item
    Route::apiResource('type.trackable-item.track-log', TrackLogsController::class) //api/type/{type}/trackable-item/{trackable_item}/track-log/{track_log}
        ->only(['index', 'store', 'update', 'destroy', 'show']); // track log
    // 查詢當日所有 track log
    Route::get('type/{typeId}/trackable-item/{trackable_item_id}/track-log-by-date', [TrackLogsController::class, 'getLogsByDate']); //api/type/{type}/trackable-item/{trackable_item}/track-log-by-date?date={date}
});