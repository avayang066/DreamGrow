<?php

namespace App\Services;

use App\Models\Type;
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

    // 單一 trackable_items 的所有 track_logs
    public function getTrackLogs($userId, $typeId, $trackable_item_id)
    {

        $trackableItem = TrackableItem::where('type_id', $typeId)
            ->where('id', $trackable_item_id)
            ->first();

        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found or type mismatch'];
            return $this;
        }

        $trackLogs = TrackLog::where('user_id', $userId)
            ->where('trackable_item_id', $trackable_item_id)
            ->get();

        $this->response = $trackLogs;

        return $this;
    }

    public function store($request, $typeId, $trackable_item_id)
    {
        // trackable_item_id 已在 Controller 中取得，不須再驗證一次，確保存在就好，否則會有「網址參數和 body 參數不一致」的風險
        if (
            !Type::where('id', $typeId)->exists()
            && !TrackableItem::where('id', $trackable_item_id)->exists()
        ) {
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
        $trackableItem->exp += $trackLogs->exp_gained; // 累加本次 exp_gained

        $achievement_message = null; // 預設 null 這樣如果沒有達成成就就不會回傳訊息

        // $daysInTrackLog = TrackLog::where('trackable_item_id', $trackable_item_id)->count();

        // if ($daysInTrackLog == $trackableItem->streak_days_required) {
        //     $trackableItem->exp += $trackableItem->streak_bonus_exp;
        //     $achievement_message = ['message' => 'Get the achievement ' . $trackableItem->achievement_text .'！'];
        // }

        // 取得最近 N 天日期
        $requiredDays = $trackableItem->streak_days_required;
        $dates = [];
        for ($i = 0; $i < $requiredDays; $i++) {
            $dates[] = Carbon::today()->subDays($i)->format('Y-m-d');
        }

        // 查詢這些日期是否每天有 log
        $logDates = TrackLog::where('trackable_item_id', $trackable_item_id)
        ->whereDate('created_at', '>=', Carbon::today()->subDays($requiredDays - 1))
        ->select(DB::raw('Date(created_at) as log_date'))
        ->groupBy('log_date')
        ->pluck('log_date')
        ->toArray();

        // 檢查是否每一天都有 Log
        $allDaysLogged = !array_diff($dates, $logDates);

        if ($allDaysLogged) {
            $trackableItem->exp += $trackableItem->streak_bonus_exp;
            $achievement_message = ['message' => 'Get the achievement ' . $trackableItem->achievement_text .'！'];
        }

        while ($trackableItem->exp >= 30) {
            $trackableItem->level += 1;
            $trackableItem->exp = 0;
        }

        $trackableItem->save();

        $this->response = [
            'track_log' => $trackLogs,
            'achievement_message' => $achievement_message
        ];

        return $this;
    }

    public function update($userId, $request, $typeId, $trackable_item_id, $track_log_id)
    {
        if (!Type::where('id', $typeId)->exists()) {
            $this->response = ['message' => 'Type does not exist'];
            return false;
        }

        $trackableItem = TrackLog::where('user_id', $userId)
            ->where('trackable_item_id', $trackable_item_id)
            ->where('id', $track_log_id)
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