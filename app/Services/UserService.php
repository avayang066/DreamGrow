<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;


class UserService
{
    // 註冊
    public function register(array $data)
    {
        // 驗證
        $validated = validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            // password 若增加 confirmed，會要求有 password_confirmation 欄位二次驗證
        ])->validate();
        // 密碼加密
        $validated['password'] = Hash::make($validated['password']);
        // 用戶註冊的功能
        $user = User::create($validated);
        // 記錄註冊事件
        Log::info('User registered', ['user_id' => $user->id, 'email' => $user->email]);
        return $user;
    }

    // 登入
    public function login(array $data)
    {
        $validated = validator($data, [
            'email' => 'required|email',
            'password' => 'required|string',
        ])->validate();

        if (!Auth::attempt($validated)) {
            return response()->json(['message' => '帳號或密碼錯誤'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => '登入成功',
            'token' => $token,
            'user' => $user,
        ]);
        // Log::info('User login attempt', ['email' => $validated['email'], 'success' => $result]);
        // return $result;
    }

    public function logout()
    {
        // Auth::logout();
        // Log::info('User logged out', ['user_id' => Auth::id()]);
        // return true;

        // 移除目前登入者的 token
        Auth::user()->currentAccessToken()->delete();
        return response()->json(['message' => '已登出']);
    }
}
