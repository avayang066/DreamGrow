<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackableItem extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(TrackLog ::class, 'trackable_item_id', 'id');
    }

    // 如果想取得 logs，可以用 logs()，如果要計算 exp_gained 總和，建議寫一個 accessor 或方法
    public function totalExpFromLogs()
    {
        return min($this->logs()->sum('exp_gained'), 300);
    }

    protected $fillable = [
        'user_id',
        'type_id',
        'name',
        'streak_days_required',
        'streak_bonus_exp',
        'achievement_text'
    ];

    public function dataFormat()
    {
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'type_id' => $this->type_id,
            'exp' => $this->exp,
            'level' => $this->level,
            'streak_days_required' => $this->streak_days_required,
            'streak_bonus_exp' => $this->streak_bonus_exp,
            'achievement_text' => $this->achievement_text
        ];
    }
}
