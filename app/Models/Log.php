<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function trackableItem()
    {
        return $this->belongsTo(TrackableItem::class, 'trackable_item_id', 'id');
    }

    protected $fillable = [
        'user_id',
        'type_id',
        'trackable_item_id',
        'content',
        'exp_gained',
        'date',
        'level_before',
        'level_after',
        'note'
    ];

    public function dataFormat()
    {
        return [
            'user_id' => $this->user->id,
            'type_id' => $this->type->id,
            'trackable_item_id' => $this->trackable_item_id,
            'content' => $this->content,
            'exp_gained' => $this->exp_gained,
            'date' => $this->date,
            // 'level_before' => $this->level_before,
            // 'level_after' => $this->level_after,
            'note' => $this->note
        ];
    }
}