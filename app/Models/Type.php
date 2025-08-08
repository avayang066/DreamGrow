<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function exp()
    {
        return $this->hasOne(TrackableItem::class, 'type_id', 'id');
    }

    public function dataFormat()
    {
        $exp = $this->exp; // 先取得 exp 關聯
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'exp' => [
                'level' => $exp ? $exp->level : null,
                'exp' => $exp ? $exp->exp : null,
                'achievement_text' => $exp ? $exp->achievement_text : null,
            ]
        ];
    }
}