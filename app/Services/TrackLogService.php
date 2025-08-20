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
        $currentStreak = $this->getCurrentStreak($trackableItem);

        $this->response = [
            'track_log' => $trackLogs,
            'current_streak' => $currentStreak,
            'achievement_message' => $trackableItem->ifachieved ? ['message' => 'Get the achievement ' . $trackableItem->achievement_text . '！'] : null
        ];

        return $this;
    }

    // exp 增加減少和升等與降等
    // 這裡的 exp_change 是正數或負數，正數表示
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

    // 判斷需要的連續天數，已累積幾天
    private function getCurrentStreak($trackableItem)
    {
        $logDates = TrackLog::where('trackable_item_id', $trackableItem->id)
            ->select(DB::raw('Date(created_at) as log_date'))
            ->groupBy('log_date')
            ->orderBy('log_date')
            ->pluck('log_date')
            ->toArray();

        if (empty($logDates)) {
            return 0;
        }

        $streak = 1;
        $maxStreak = 1;
        $requiredDays = $trackableItem->streak_days_required;

        // $logDates 是 tracklog 陣列 // 用 curr、prev 判斷是否有連續天數
        for ($i = 1; $i < count($logDates); $i++) {
            $prev = Carbon::parse($logDates[$i - 1]);
            $curr = Carbon::parse($logDates[$i]);
            if ($curr->diffInDays($prev) == 1) {
                if ($streak <= $requiredDays) {
                    $streak++;
                    $maxStreak = max($maxStreak, $streak);
                    TrackableItem::where('id', $trackableItem->id)->update(['streak_days' => $maxStreak]);
                }
            } else {
                $streak = 1;
            }
        }
        return $maxStreak;
    }

    // 判斷連續天數是否有獲得成就
    private function updateAchievement($trackableItem)
    {
        // 取得需要達成成就的「連續天數」
        $requiredDays = $trackableItem->streak_days_required;

        // 取得所有 log 的日期（去重複，排序）
        $logDates = TrackLog::where('trackable_item_id', $trackableItem->id)
            // ->whereDate('created_at', '>=', Carbon::today()->subDays($requiredDays - 1))
            ->select(DB::raw('Date(created_at) as log_date'))
            ->groupBy('log_date')
            ->pluck('log_date')
            ->toArray();

        // 判斷是否有 N 天
        $streak = 1;
        for ($i = 1; $i < count($logDates); $i++) {
            $prev = Carbon::parse($logDates[$i - 1]);
            $curr = Carbon::parse($logDates[$i]);
            if ($curr->diffInDays($prev) == 1) {
                $streak++;
                if ($streak >= $requiredDays) {
                    $trackableItem->exp += $trackableItem->streak_bonus_exp;
                    $trackableItem->ifachieved = 1;
                    $trackableItem->save();
                    return ['message' => 'Get the achievement ' . $trackableItem->achievement_text . '！'];
                }
            }
        }
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
        $currentStreak = $this->getCurrentStreak($trackableItem);

        $this->response = [
            'track_log' => $trackLog,
            'current_streak' => $currentStreak,
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