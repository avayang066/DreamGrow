<?php

namespace App\Services;

use App\Models\Type;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class TypeService
{
    private $response;
    private $request;

    function ifUserLogin($userId)
    {
        return auth()->id() == $userId;
    }

    public function getType($userId)
    {
        $types = Type::with('user')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return $item->dataFormat();
            });

        $this->response = $types;

        return $this;
    }

    public function store($userId, array $data)
    {
        if (!$this->ifUserLogin($userId)) {
            $this->response = ['message' => '禁止存取'];
            return $this;
        }

        $validated = validator($data, [
            'name' => 'required|string|max:255',
        ])->validate();


        $type = Type::create([
            'user_id' => $userId,
            'name' => $validated['name'],
        ]);
        $this->response = $type;

        return $this;
    }

    public function update($userId, $typeId, array $data)
    {
        if ($this->response) {
            return $this->response;
        }

        $type = Type::where('id', $typeId)
            ->where('user_id', $userId)
            ->first();
        if (!$type) {
            return response()->json(['message' => '類別不存在'], 404);
        }

        $validated = validator($data, [
            'name' => 'required|string|max:255',
        ])->validate();

        $type->update(['name' => $validated['name']]);
        $this->response = $type;

        return $this;
    }

    public function destroy($userId, $typeId)
    {
        $type = Type::where('id', $typeId)
            ->where('user_id', $userId)
            ->first();

        if (!$type) {
            $this->response = ['success' => false, 'message' => '類型不存在'];
            return $this;
        }

        $type->delete();
        $this->response = ['success' => true, 'message' => '刪除成功'];
        return $this;
    }

public function show($userId, $typeId)
{
    $type = Type::where('id', $typeId)
        ->where('user_id', $userId)
        ->first();

    if (!$type) {
        $this->response = ['message' => '類型不存在'];
        return $this;
    }

    $this->response = $type->dataFormat();
    return $this;
}

    public function getResponse()
    {
        return $this->response;
    }
}