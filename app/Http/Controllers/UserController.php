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
        try {
            $user = $userService->register($request->all());
            Auth::login($user);
            return response()->json(['message' => '註冊成功', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '註冊失敗',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    // 登入
    public function login(Request $request, UserService $userService)
    {
        try {
            if ($userService->login($request->all())) {
                return response()->json([
                    'message' => '登入成功',
                     'user' => Auth::user(),
                    ]);
            }
            return response()->json(['message' => '帳號或密碼錯誤'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '登入失敗',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    // 登出
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => '登出成功']);
    }
}
