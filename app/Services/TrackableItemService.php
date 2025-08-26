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
        'create' => [
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
        ],
        'update' => [
            'name' => 'required|exists:trackable_items,name',
            'type_id' => 'required|exists:types,id',
        ]
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

    public function getTrackableItem($typeId)
    {
        // DB::enableQueryLog();
        // DB::raw();

        $trackableItems = TrackableItem::where('user_id', $this->userId)
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