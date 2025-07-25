<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/fortest', function () {
//     // echo "123";
//     function test()
//     {
//         echo "123";
//     }
//     test();
// });

Route::post('/signup', [UserController::class, 'userRegister'])->name('user.register');
Route::post('/login', [UserController::class, 'login'])->name('user.signin');
Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');

// Route::middleware(['auth'])->group(function () {
//     Route::post('/createtype/{id}', [TypeController::class, 'createTypeForUser'])->name('user.createtype');
//     Route::get('/gettype/{id}', [TypeController::class, 'getType'])->name('user.gettype');
// });

Route::post('/createtype/{id}', [TypeController::class, 'createTypeForUser'])->name('user.createtype');
Route::get('/gettype/{id}', [TypeController::class, 'getType'])->name('user.gettype');