<?php

namespace App\Services;

use App\Traits\RulesTrait;
use Illuminate\Support\Facades\DB;
use App\Models\TrackableItem;
use App\Models\TrackLog;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class TrackableItemService
{
    use RulesTrait;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type_id' => 'required|exists:types,id',
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

        if (!$this->userId) {
            $this->response = ['message' => '未登入'];
        }
    }

    function validateTypeExists($typeId = null)
    {
        $typeId = $typeId ?? $this->request->input('id');

        if (!Type::where('id', $typeId)->exists()) {
            $this->response = ['message' => 'Type does not exist'];
            return false;
        }
        return true;
    }

    public function getTrackableItem($typeId, $userId)
    {
        // DB::enableQueryLog();
        // DB::raw();

        if (!$this->validateTypeExists($typeId)) {
            return $this;
        }

        $trackableItems = TrackableItem::where('user_id', $userId)
            ->where('type_id', $typeId)
            ->get();
        $this->response = $trackableItems;

        $levelSumByType_id = collect($trackableItems)
            ->groupBy('type_id')
            ->map(function ($group) {
                return collect(value: $group)->sum(function ($trackableItem) {
                    return $trackableItem['level'] ?? 0;
                });
            });

        $this->response = [
            'types' => $trackableItems,
            'level_sum_by_type_id' => $levelSumByType_id
        ];

        // dd(DB::getQueryLog());
        return $this;
    }

    public function store(Request $request, $typeId)
    {
        if (!$this->validateTypeExists($typeId)) {
            return $this;
        }

        $data = $request->only((new TrackableItem())->getFillable());
        // 強制覆蓋 user_id 與 type_id（避免被竄改）
        $data['user_id'] = auth()->id();
        $data['type_id'] = $typeId;
        $data['exp'] = 0;
        $data['level'] = 1;
        $trackableItem = TrackableItem::create($data);

        $this->response = $trackableItem;
        return $this;
    }

    public function update(Request $request, $typeId, $trackable_item_id)
    {
        if (!$this->validateTypeExists($typeId)) {
            return $this;
        }

        // DB::enableQueryLog();
        $userId = auth()->id();
        $trackableItem = TrackableItem::where('user_id', $userId)
            ->where('type_id', $typeId)
            ->where('id', $trackable_item_id)
            ->first();

        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found'];
            return $this;
        }

        // 只允許更新特定欄位
        $data = $request->only($trackableItem->getFillable());
        $trackableItem->update($data);

        $this->response = $trackableItem;
        // dd(DB::getQueryLog());
        return $this;
    }

    public function destroy($userId, $typeId, $trackable_item_id)
    {
        if (!$this->validateTypeExists($typeId)) {
            return $this;
        }

        $trackableItem = TrackableItem::where('user_id', $userId)
            ->where('type_id', $typeId)
            ->where('id', $trackable_item_id)
            ->first();

        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found'];
            return $this;
        }

        $trackableItem->delete();
        return $this;
    }

    public function show($typeId, $trackable_item_id)
    {
        if (!$this->validateTypeExists($typeId)) {
            return $this;
        }

        $trackableItem = TrackableItem::where('user_id', $this->userId)
            ->where('type_id', $typeId)
            ->where('id', $trackable_item_id)
            ->first();

        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found'];
            return $this;
        }

        $this->response = $trackableItem->dataFormat();
        return $this;
    }

    public function getResponse()
    {
        return response()->json($this->response);
    }

}