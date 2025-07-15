<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 註冊
    public function register(Request $request, UserService $userService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = $userService->register($validated);
        Auth::login($user);
        return response()->json(['message' => '註冊成功', 'user' => $user]);
    }

    // 登入
    public function login(Request $request, UserService $userService)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($userService->login($credentials)) {
            return response()->json(['message' => '登入成功', 'user' => Auth::user()]);
        }
        return response()->json(['message' => '帳號或密碼錯誤'], 401);
    }

    // 登出
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => '登出成功']);
    }
}
