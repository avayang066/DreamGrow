<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    // 註冊
    public function register(array $data)
    {
        // 驗證
        $validated = validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
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
        $result = Auth::attempt($validated);
        Log::info('User login attempt', ['email' => $validated['email'], 'success' => $result]);
        return $result;
    }

    public function logout()
    {
        Auth::logout();
        Log::info('User logged out', ['user_id' => Auth::id()]);
        return true;
    }
}
