<?php

namespace App\Services;

use App\Models\Type;
use App\Models\TrackLog;
use App\Models\TrackableItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        $this->updateExpAndLevel($trackableItem, $trackLogs->exp_gained);
        $this->updateAchievement($trackableItem);

        $this->response = [
            'track_log' => $trackLogs,
            'achievement_message' => $trackableItem->ifachieved ? ['message' => 'Get the achievement ' . $trackableItem->achievement_text . '！'] : null
        ];

        return $this;
    }

    private function updateExpAndLevel(TrackableItem $item, $expChange)
    {
        $item->exp += $expChange;
        // 處理升級
        while ($item->exp >= 30) {
            $item->level += 1;
            $item->exp -= 30;
        }
        // 處理降級
        while ($item->exp < 0) {
            if ($item->level > 1) {
                $item->level -= 1;
                $item->exp += 30;
            } else {
                $item->exp = 0;
                break;
            }
        }
        $item->save();
    }

    // 判斷連續天數成就
    private function updateAchievement(TrackableItem $item)
    {
        $requiredDays = $item->streak_days_required;
        $dates = [];
        for ($i = 0; $i < $requiredDays; $i++) {
            $dates[] = Carbon::today()->subDays($i)->format('Y-m-d');
        }
        $logDates = TrackLog::where('trackable_item_id', $item->id)
            ->whereDate('created_at', '>=', Carbon::today()->subDays($requiredDays - 1))
            ->selectRaw('DATE(created_at) as log_date')
            ->groupBy('log_date')
            ->pluck('log_date')
            ->toArray();
        $item->ifachieved = !array_diff($dates, $logDates) ? 1 : 0;
        $item->save();
    }


    private function checkStreakAchievement($trackableItem)
    {
        // 取得需要達成成就的「連續天數」
        $requiredDays = $trackableItem->streak_days_required;
        // 產生最近 N 天（含今天）的日期陣列
        $dates = [];
        for ($i = 0; $i < $requiredDays; $i++) {
            $dates[] = Carbon::today()->subDays($i)->format('Y-m-d');
        }

        // 查詢資料庫，取得這 N 天內有 log 的所有日期（去重複）
        $logDates = TrackLog::where('trackable_item_id', $trackableItem->id)
            ->whereDate('created_at', '>=', Carbon::today()->subDays($requiredDays - 1))
            ->select(DB::raw('Date(created_at) as log_date'))
            ->groupBy('log_date')
            ->pluck('log_date')
            ->toArray();

        // 判斷 $dates 陣列裡的每一天，是否都在 $logDates 裡（也就是這 N 天每天都有 log）
        $allDaysLogged = !array_diff($dates, $logDates);

        if ($allDaysLogged) {
            $trackableItem->exp += $trackableItem->streak_bonus_exp;
            $trackableItem->ifachieved = 1;
            $trackableItem->save();
            return ['message' => 'Get the achievement ' . $trackableItem->achievement_text . '！'];
        }

        return null;
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

    public function destroy($userId, $type_id, $trackable_item_id, $track_log_id)
    {
        $trackLog = TrackLog::where('user_id', $userId)
            ->where('trackable_item_id', $trackable_item_id)
            ->where('id', $track_log_id)
            ->first();

        if (!$trackLog) {
            $this->response = ['message' => 'Trackable item not found'];
            return $this;
        }

        // 取得對應的 TrackableItem
        $trackableItem = TrackableItem::find($trackable_item_id);

        $trackLog->delete();

        $this->updateExpAndLevel($trackableItem, -$trackLog->exp_gained);
        $this->updateAchievement($trackableItem);

        $this->response = [
            'track_log' => $trackLog,
            'achievement_message' => $trackableItem->ifachieved ? ['message' => 'Get the achievement ' . $trackableItem->achievement_text . '！'] : null
        ];

        return $this;
    }

    public function show($typeId, $trackable_item_id, $track_log_id)
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