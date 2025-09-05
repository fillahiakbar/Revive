<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AnimeLink extends Model
{
   protected $fillable = [
        'mal_id','title','poster','episodes','synopsis','season','year','type','genres',
        'title_english','imdb_id','mal_score','imdb_score'
    ];

    protected $casts = [
        'click_count' => 'integer',

        'genres' => 'array',
    ];


    public function getGenresAttribute($value)
    {
       
        if (is_array($this->attributes['genres'] ?? null)) return $this->attributes['genres'];


        if (is_string($value)) {
            $arr = array_filter(array_map(
                fn($x) => trim($x),
                preg_split('/,|\|/',$value) ?: []
            ));
    
            $this->attributes['genres'] = $arr;
            return $arr;
        }

        return [];
    }


    public function types()
    {
        return $this->belongsToMany(AnimeType::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'anime_link_id');
    }

    protected static function booted()
    {
        static::deleting(function ($animeLink) {
            $animeLink->batches()->each(function ($batch) {
                $batch->batchLinks()->delete();
            });
            $animeLink->batches()->delete();
        });

        static::creating(function ($anime) {
            $anime->slug = Str::slug($anime->title);
        });
    }

    public function generateRssFile()
    {
        $rssXml = View::make('rss.feed', ['anime' => $this->load('batches.batchLinks')])->render();

        $path = public_path('rss/' . $this->slug . '.xml');

        File::ensureDirectoryExists(public_path('rss'));
        File::put($path, $rssXml);
    }

    public function scopePeriod(Builder $query, string $period): Builder
{
    $now = now();

    return match ($period) {
        'weekly'  => $query->where('created_at', '>=', $now->copy()->startOfWeek()),
        'monthly' => $query->where('created_at', '>=', $now->copy()->startOfMonth()),
        default   => $query, // all_time
    };
}
    public function comments()
    {
        return $this->hasMany(Comment::class, 'anime_link_id');
    }

    public function relatedGroup()
    {
        return $this->belongsTo(\App\Models\RelateAnimeGroup::class, 'related_anime_group_id');
    }

    public function relatedAnimes()
    {
        return $this->hasMany(RelatedAnime::class);
    }

    public function visits()
{
    return $this->hasMany(\App\Models\AnimeVisit::class);
}

/**
 * Filter berdasarkan periode waktu terhadap visited_date pada anime_visits.
 * Mengembalikan query yang hanya memuat anime yang MEMILIKI kunjungan di periode itu.
 */
public function scopeWhereHasVisitsInPeriod(Builder $query, string $period): Builder
{
    $now = now();

    return match ($period) {
        'weekly'  => $query->whereHas('visits', fn ($q) => $q->where('visited_date', '>=', $now->copy()->startOfWeek()->toDateString())),
        'monthly' => $query->whereHas('visits', fn ($q) => $q->where('visited_date', '>=', $now->copy()->startOfMonth()->toDateString())),
        default   => $query, // all_time: tidak difilter keberadaan visit
    };
}

/**
 * Tambahkan kolom agregasi period_clicks (sum count pada periode).
 * Menggunakan alias 'period_clicks' agar bisa ditampilkan/disort di Filament.
 */
public function scopeWithPeriodClicks(Builder $query, string $period): Builder
{
    $now = now();

    return $query->withSum(
        ['visits as period_clicks' => function ($q) use ($period, $now) {
            if ($period === 'weekly') {
                $q->where('visited_date', '>=', $now->copy()->startOfWeek()->toDateString());
            } elseif ($period === 'monthly') {
                $q->where('visited_date', '>=', $now->copy()->startOfMonth()->toDateString());
            } // all_time: tanpa filter → total sepanjang waktu
        }],
        'count'
    );
}
}

