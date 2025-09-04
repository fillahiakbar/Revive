<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['most_visited_period'];

    public static function getMostVisitedPeriod(): string
    {
        return cache()->rememberForever('most_visited_period', function () {
            return optional(self::query()->first())->most_visited_period ?? 'all_time';
        });
    }

    public static function setMostVisitedPeriod(string $period): void
    {
        $setting = self::query()->first() ?? new self();
        $setting->most_visited_period = $period;
        $setting->save();

        cache()->forget('most_visited_period');
        // (opsional) hapus cache daftar landing per periode
        cache()->forget('most_visited:list:weekly');
        cache()->forget('most_visited:list:monthly');
        cache()->forget('most_visited:list:all_time');
    }
}