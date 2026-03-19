<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefStat extends Model
{
    protected $fillable = [
        'user_id',
        'total_click',
        'unique_click',
        'anime_shared',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
