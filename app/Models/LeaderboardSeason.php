<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardSeason extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function active()
    {
        return static::where('is_active', true)->first();
    }

    public function refStats()
    {
        return $this->hasMany(RefStat::class, 'season_id');
    }

    public function refClicks()
    {
        return $this->hasMany(RefClick::class, 'season_id');
    }
}
