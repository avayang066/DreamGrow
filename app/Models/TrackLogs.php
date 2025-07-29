<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackLogs extends Model
{
    use HasFactory;

    public function dataFormat()
    {
        return [
            'trackable_item_id' => $this->trackable_item_id,
            'action' => $this->action,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
