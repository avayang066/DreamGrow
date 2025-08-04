<?php

namespace App\Services;

use App\Models\TrackLog;
use App\Models\TrackableItem;
use Illuminate\Support\Carbon;

class TrackLogService
{
    private $response;
    private $request;

    function ifTrackableItemExists($trackableItemId)
    {
        return TrackLog::where('trackable_item_id', $trackableItemId)->exists();
    }

    public function getTrackLogs($userId, $typeId, $trackable_item_id)
    {
        $trackLogs = TrackLog::where('user_id', $userId)
            ->where('type_id', $typeId)
            ->where('trackable_item_id', $trackable_item_id)
            ->get();
        $this->response = $trackLogs;

        return $this;
    }

    public function store($request, $typeId, $trackable_item_id)
    {
        // trackable_item_id 已在 Controller 中取得，不須再驗證一次，確保存在就好，否則會有「網址參數和 body 參數不一致」的風險
        if (TrackableItem::where('type_id', $typeId)
            ->where('id', $trackable_item_id)->exists()) {
            $this->response = ['message' => 'trackable item does not exist'];
            return $this;
        }

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        // 取得 fillable 欄位的所有資料
        $trackLogs = new TrackLog();
        $data = $request->only($trackLogs->getFillable());

        // 強制覆蓋 user_id 與 trackable_item_id（避免被竄改）
        $data['user_id'] = auth()->id();
        $data['trackable_item_id'] = $trackable_item_id;

        $trackLogs->fill($data);
        $trackLogs->save();

        $trackableItem = TrackableItem::find($trackable_item_id);
        $exp_gained = TrackLog::where('trackable_item_id', $trackable_item_id)
            ->sum('exp_gained');

        $daysInTrackLog = TrackLog::where('trackable_item_id', $trackable_item_id)->count();
        $achievement_exp = ($daysInTrackLog == $trackableItem->streak_days_required) ? $trackableItem->streak_bonus_exp : 0;

        $trackableItem->exp = $exp_gained + $achievement_exp;
        $trackableItem->save();

        $this->response = $trackLogs;
        return $this;
    }

    public function update($userId, $request, $typeId, $trackable_item_id)
    {
        $trackableItem = TrackLog::where('user_id', $userId)
            ->where('type_id', $typeId)
            ->where('trackable_item_id', $trackable_item_id)
            ->first();
        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found'];
            return $this;
        }

        // 只允許更新特定欄位
        $data = $request->only($trackableItem->getFillable());
        $trackableItem->update($data);

        $this->response = $trackableItem;
        return $this;
    }

    public function destroy($userId, $typeId, $trackable_item_id)
    {
        $trackableItem = TrackLog::where('user_id', $userId)
            ->where('type_id', $typeId)
            ->where('trackable_item_id', $trackable_item_id)
            ->first();
        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found'];
            return $this;
        }

        $trackableItem->delete();
        return $this;
    }

    public function show($trackable_item_id, $typeId, $track_log_id)
    {
        $trackLog = TrackLog::where('trackable_item_id', $trackable_item_id)
            ->where('type_id', $typeId)
            ->where('id', $track_log_id)
            ->first();

        if (!$trackLog) {
            return response()->json(['message' => 'Track log not found'], 404);
        }

        $this->response = $trackLog;
        return $this;
    }

    public function getResponse()
    {
        return response()->json($this->response);
    }
}