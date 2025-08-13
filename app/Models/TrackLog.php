<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackLog extends Model
{
    use HasFactory;

    public function trackableItem()
    {
        return $this->belongsTo(TrackableItem::class, 'trackable_item_id', 'id');
    }

    protected $fillable = [
        'user_id',
        'trackable_item_id',
        'content',
        'exp_gained',
        'created_at'
    ];


    public function dataFormat()
    {
        return [
            'user_id' => $this->user_id,
            'trackable_item_id' => $this->trackable_item_id,
            'content' => $this->content,
            'exp_gained' => $this->exp_gained
        ];
    }
}