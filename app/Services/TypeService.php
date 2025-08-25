<?php

namespace App\Services;

use App\Models\Type;
use App\Traits\RulesTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class TypeService
{
    use RulesTrait;

    protected $rules = [
        'name' => [
            'name' => 'required|string|max:255',
        ],
    ];

    private $response;
    private $request;
    protected $changeErrorName = [];
    protected $messages = [];

    function __construct(Request $request, $dataId = null)
    {
        $this->request = $request;
        $this->dataId = $dataId;
        $this->userId = auth()->id();

        if(!$this->userId) {
            $this->response = ['message' => '未登入'];
        }
    }

    public function getType()
    {
        $types = Type::with('user')
            ->where('user_id', $this->userId)
            ->get()
            ->map(function ($item) {
                return $item->dataFormat();
            });

        $this->response = $types;

        return $this;
    }

    public function store()
    {
        $type = Type::create([
            'user_id' => $this->userId,
            'name' => $this->request['name'],
        ]);

        $this->response = $type;

        return $this;
    }

    public function update($typeId)
    {
        if ($this->response) {
            return $this->response;
        }

        $type = Type::where('id', $typeId)
            ->where('user_id', $this->userId)
            ->first();
        if (!$type) {
            return response()->json(['message' => '類別不存在'], 404);
        }

        $type->update(['name' => $this->request['name']]);
        $this->response = $type;

        return $this;
    }

    public function destroy($typeId)
    {
        $type = Type::where('id', $typeId)
            ->where('user_id', $this->userId)
            ->first();
        if (!$type) {
            $this->response = ['success' => false, 'message' => '類型不存在'];
            return $this;
        }

        $type->delete();

        $this->response = ['success' => true, 'message' => '刪除成功'];
        return $this;
    }

    public function show($typeId)
    {
        $type = Type::where('id', $typeId)
            ->where('user_id', $this->userId)
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