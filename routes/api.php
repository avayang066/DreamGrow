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

// Route::get('/debug', function () {
//     return response()->json(['status' => 'API 跑得起來']);
// });

// 會員 & User
Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'userRegister')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::middleware('auth::sanctum')->post('/logout', 'logout')->name('logout');
    Route::middleware('auth::sanctum')->get('/user', function (Request $request) {
        return $request->user();
    })->name('user.profile');
});

// 種類頁面
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('type', TypeController::class)
        ->only(['index', 'store', 'update', 'destroy']); // type
    Route::apiResource('type.trackable-item', TrackableItemController::class) // /api/type/{type}/trackable-item/{trackable-item}
        ->only(['index', 'store', 'update', 'destroy']); // trackable-item
    Route::apiResource('type.trackable-item.track-log', TrackLogsController::class) // 1. /api/type/{type}/trackable-item  2. api/trackable-item/{trackable_item}/track-log/{track_log}
        ->only(['index', 'store', 'update', 'destroy', 'show']); // track log
});

// 取得特定 trackable item，包含 trackLog 加起來的經驗值與成就經驗值 總和
Route::middleware('auth:sanctum')->get(
    'type/{type}/trackable-item/{trackable_item}/exp',
    [TrackableItemController::class, 'getTrackableItemExpFromTrackLog']
);

