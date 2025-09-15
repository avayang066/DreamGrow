<?php

namespace App\Services;

use App\Traits\RulesTrait;
use App\Models\Type;
use App\Models\TrackableItem;
use App\Models\TrackLog;
use App\Models\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class TrackLogService
{
    use RulesTrait;

    private $response;
    private $request;
    protected $changeErrorName = [];
    protected $messages = [];

    protected $rules = [
        'create' => [
            'content' => 'required|string|max:255',
            'trackable_item_id' => 'required|exists:trackable_items,id',
        ],
        'update' => [
            'content' => 'required|exists:track_logs,content',
            'trackable_item_id' => 'required|exists:trackable_items,id',
        ]
    ];

    function __construct(Request $request, $dataId = null)
    {
        $this->request = $request;
        $this->dataId = $dataId;
        $this->userId = auth()->id();

        if (!$this->userId) {
            $this->response = ['message' => '未登入'];
        }
    }

    // 單一 trackable_items 的所有 track_logs
    public function getTrackLogs($typeId, $trackable_item_id)
    {

        $trackableItem = TrackableItem::where('type_id', $typeId)
            ->where('id', $trackable_item_id)
            ->first();

        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found or type mismatch'];
            return $this;
        }

        $trackLogs = TrackLog::where('user_id', $this->userId)
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

        $trackableItem = TrackableItem::find($trackable_item_id);
        $level_before = $trackableItem->level;

        $data = array_merge(
            $request->only((new Log())->getFillable()),
            [
                'user_id' => auth()->id(),
                'trackable_item_id' => $trackable_item_id,
            ]
        );

        $trackLogs = TrackLog::create($data);

        $this->updateExpAndLevel($trackableItem, $trackLogs->exp_gained);
        $this->updateAchievement($trackableItem);
        $currentStreak = $this->calculateMaxStreak($trackableItem);

        $trackableItem->refresh();
        $level_after = $trackableItem->level;

        $this->createLog($trackable_item_id, $level_before, $level_after, 'create');

        $this->response = [
            'message' => 'Track log created successfully.',
            'track_log' => $trackLogs,
            'current_streak' => $currentStreak,
            'achievement_message' => $trackableItem->ifachieved ? ['message' => 'Get the achievement ' . $trackableItem->achievement_text . '！'] : null
        ];

        return $this;
    }

    public function createLog($trackable_item_id, $level_before = null, $level_after = null, $action = null)
    {
        $trackableItem = TrackableItem::find($trackable_item_id);

        $data = array_merge(
            $this->request->only((new Log())->getFillable()),
            [
                'user_id' => auth()->id(),
                'type_id' => $trackableItem ? $trackableItem->type_id : null,
                'trackable_item_id' => $trackable_item_id,
                'level_before' => $level_before,
                'level_after' => $level_after,
                'action' => $action,            ]
        );

        $Logs = Log::create($data);

        $this->response = [
            'message' => 'Logs created successfully.',
            'Logs' => $Logs
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

    // 判斷已累積的最大天數
    private function calculateMaxStreak($trackableItem)
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

        // $logDates 是 tracklog 陣列 // 用 curr、prev 判斷是否有連續天數
        for ($i = 1; $i < count($logDates); $i++) {
            $prev = Carbon::parse($logDates[$i - 1]);
            $curr = Carbon::parse($logDates[$i]);
            if ($curr->diffInDays($prev) == 1) {
                $streak++;
                $maxStreak = max($maxStreak, $streak);
            } else {
                $streak = 1;
            }
        }

        $requiredDays = $trackableItem->streak_days_required;
        $maxStreak = min($maxStreak, $requiredDays); // 將最大天數限制在成就天數內

        $trackableItem->streak_days = $maxStreak;
        $trackableItem->save();

        return $maxStreak;
    }

    // 判斷連續天數是否有獲得成就
    private function updateAchievement($trackableItem)
    {
        $requiredDays = $trackableItem->streak_days_required;
        $maxStreak = $this->calculateMaxStreak($trackableItem);

        if ($maxStreak >= $requiredDays) {
            if (!$trackableItem->ifachieved) {
                $trackableItem->exp += $trackableItem->streak_bonus_exp;
            }
            $trackableItem->ifachieved = 1;
            $trackableItem->save();
            return ['message' => 'Get the achievement ' . $trackableItem->achievement_text . '！'];
        } else {
            if ($trackableItem->ifachieved) {
                $trackableItem->exp -= $trackableItem->streak_bonus_exp;
            }
            $trackableItem->ifachieved = 0;
            $trackableItem->save();
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

        $this->createLog($trackable_item_id, null, null, 'update');

        $this->response = $trackableItem;
        return $this;
    }

    public function destroy($type_id, $trackable_item_id, $track_log_id)
    {
        $trackLog = TrackLog::where('user_id', $this->userId)
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
        $currentStreak = $this->calculateMaxStreak($trackableItem);

        $OriginalContent = $trackLog->content;
        $this->createLog($trackable_item_id, null, null, 'delete', $OriginalContent);

        $this->response = [
            'message' => 'Track log deleted successfully.',
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

    public function getLogsByDate(Request $request, $typeId, $trackable_item_id)
    {
        $trackableItem = TrackableItem::where('type_id', $typeId)
            ->where('id', $trackable_item_id)
            ->first();

        if (!$trackableItem) {
            $this->response = ['message' => 'Trackable item not found'];
            return $this;
        }

        $date = $request->query('date');
        $tracklog = Tracklog::where('user_id', $this->userId)
            ->where('trackable_item_id', $trackable_item_id)
            ->whereDate('created_at', $date)
            ->get();

        $this->response = $tracklog;
        return $this;
    }

    public function getResponse()
    {
        return response()->json($this->response);
    }
}