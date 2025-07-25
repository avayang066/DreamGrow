<?php

namespace App\Services;

use App\Models\Type;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class TypeService
{
    protected $response;

    function ifUserLogin($userId)
    {
        return auth()->id() == $userId;
    }

    // 新增類別的功能
    public function createTypeForUser($userId, $data)
    {
        // 錯誤用法，參數無法用->validate()驗證，必須要物件
        // $validated = $data->validate([
        //     'name' => 'required|string|max:255',
        // ]);

        if (!$this->ifUserLogin($userId)) {
            // 權限不符時直接回應
            $this->response = ['message' => '禁止存取'];
            return $this;
        }
        // 測試用
        // $userId = "1";

        $validated = validator($data, [
            'name' => 'required|string|max:255',
        ])->validate();

        try {
            $type = Type::create([
                'user_id' => $userId,
                'name' => $validated['name'],
            ]);

            $this->resopnse = $type;
            return $this;

        } catch (\Exception $e) {
            return response()->json([
                'message' => '類別建立失敗',
                'error' => $e->getMessage(),
            ], 400);
        }

    }

    // 將已有類別顯示在畫面上
    public function getType($userId)
    {
        try {
            if (!$this->ifUserLogin($userId)) {
                // 權限不符時直接回應
                return response()->json(['message' => '禁止存取'], 403);
            }

            $names = Type::pluck('name');
            $this->response = $names;
            // return response()->json(['name' => $names]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(),], 400);
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}