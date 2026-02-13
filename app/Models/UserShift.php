<?php

namespace App\Models;

use \App\Models\BaseModel as Model;

class UserShift extends Model
{
    protected $casts = [
        'shift_date' => 'date',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }
}
