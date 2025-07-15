<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    // 註冊
    public function register(array $data)
    {
        $validated = validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ])->validate();

        $validated['password'] = Hash::make($validated['password']);
        return User::create($validated);
    }

    // 登入
    public function login(array $data)
    {
        $validated = validator($data, [
            'email' => 'required|email',
            'password' => 'required|string',
        ])->validate();
        return Auth::attempt($validated);
    }
}
