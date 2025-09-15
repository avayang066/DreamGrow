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

    public function trackableItems()
{
    return $this->hasMany(TrackableItem::class, 'type_id', 'id');
}

    public function dataFormat()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
        ];
    }
}