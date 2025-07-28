<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 註冊
    public function userRegister(Request $request, UserService $userService)
    {
        return $userService->register($request->all());
    }

    // 登入
    public function login(Request $request, UserService $userService)
    {
        return $userService->login($request->all());
    }

    // 登出
    public function logout(UserService $userService)
    {
        return $userService->logout();
    }
}
