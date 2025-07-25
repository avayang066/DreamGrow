<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    // 註冊
    public function register(Request $request)
    {
        $user = $this->service->register($request->all());

        return response()->json([
            'message' => '註冊成功',
            'user' => $user
        ]);
    }

    // 登入
    public function login(Request $request)
    {
        return $this->service->login($request->all());
    }

    // 登出
    public function logout(Request $request)
    {
        return $this->service->logout();
    }
}