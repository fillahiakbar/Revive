<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    // ===============================
    // LIST GENRE dari DATABASE
    // ===============================
    public function genres(Request $request)
    {
        $letter = $request->get('letter', 'ALL');
        $page   = (int) $request->get('page', 1);

        $all = Cache::remember('all_anime_genres_from_db', now()->addHours(12), function () {
            $rows = AnimeLink::query()
                ->whereNotNull('genres')
                ->where('genres', '!=', '')
                ->get(['id','genres']);

            $bucket = [];
            foreach ($rows as $r) {
                $list = is_array($r->genres) ? $r->genres : (array) $r->genres;
                foreach ($list as $g) {
                    $name = trim((string)$g);
                    if ($name === '') continue;
                    $name = Str::of($name)->lower()->ucfirst()->toString();
                    $bucket[$name] = ($bucket[$name] ?? 0) + 1;
                }
            }

            $out = [];
            foreach ($bucket as $name => $cnt) {
                $out[] = [
                    'mal_id' => Str::slug($name), // dipakai sebagai slug genre
                    'name'   => $name,
                    'count'  => $cnt,
                ];
            }
            usort($out, fn($a,$b) => strcmp($a['name'], $b['name']));
            return collect($out);
        });

        $filtered = $this->filterGenresByLetter($all, $letter);

        $perPage = 100;
        $total   = $filtered->count();
        $slice   = $filtered->slice(($page - 1) * $perPage, $perPage)->values();

        return view('anime.genres', [
            'genres'       => $slice,
            'letter'       => $letter,
            'currentPage'  => $page,
            'total'        => $total,
            'perPage'      => $perPage,
            'hasMorePages' => $total > ($page * $perPage),
        ]);
    }

    private function filterGenresByLetter(Collection $genres, string $letter): Collection
    {
        if ($letter === 'ALL') return $genres;

        if ($letter === '0-9') {
            return $genres->filter(function ($g) {
                $first = substr($g['name'], 0, 1);
                return is_numeric($first);
            });
        }

        return $genres->filter(function ($g) use ($letter) {
            return strtoupper(substr($g['name'], 0, 1)) === strtoupper($letter);
        });
    }

    // ===============================
    // LIST ANIME per GENRE (DB only)
    // ===============================
   public function byGenre(string $genreSlug, Request $request)
{
    $name = Str::of($genreSlug)->replace('-', ' ')->toString();

    $alts = collect([
        $name,
        Str::title($name),
        ucfirst(strtolower($name)),
        strtoupper($name),
        strtolower($name),
        Str::replace(' ', '-', Str::title($name)),
        Str::replace(' ', '', Str::title($name)),
    ])->unique()->values();

    $q = AnimeLink::query()
        ->with('types') // <-- ambil relasi types
        ->select([
            'id','mal_id','title','title_english','poster','episodes',
            'mal_score','imdb_score','season','year','created_at','genres'
        ]);

    $q->where(function ($qq) use ($alts) {
        foreach ($alts as $g) {
            $gl = strtolower($g);

            $qq->orWhereRaw(
                "JSON_VALID(genres) AND JSON_SEARCH(LOWER(genres), 'one', ?, NULL, '$') IS NOT NULL",
                [$gl]
            );
            $qq->orWhereRaw(
                "NOT JSON_VALID(genres) AND FIND_IN_SET(?, REPLACE(LOWER(genres), ', ', ','))",
                [$gl]
            );
        }
    });

    $perPage = 24;
    $p = $q->orderByDesc('created_at')
           ->paginate($perPage)
           ->appends($request->query());

    // Mapping ke struktur komponen <x-anime.card>
    $animeList = collect($p->items())->map(function (AnimeLink $a) {
        $types = $a->types->map(fn($t) => [
            'name'  => $t->name,
            'color' => $t->color ?? '#6B7280',
        ])->toArray();

        return [
            'mal_id'         => $a->mal_id,
            'title'          => $a->title ?? $a->title_english ?? 'Unknown Title',
            'title_english'  => $a->title_english,
            'local_title'    => $a->title,
            'episodes'       => $a->episodes,
            'duration'       => null,
            'score'          => $a->mal_score ?? $a->imdb_score,
            'poster'         => $a->poster,
            'images'         => [
                'jpg' => [
                    'large_image_url' => $a->poster,
                    'image_url'       => $a->poster,
                ]
            ],
            'types'          => $types,
        ];
    });

    $pagination = [
        'current_page'      => $p->currentPage(),
        'last_visible_page' => $p->lastPage(),
        'has_next_page'     => $p->hasMorePages(),
        'items_count'       => $p->total(),
    ];

    return view('anime.by-genre', [
        'animeList'  => $animeList,
        'pagination' => $pagination,
        'genreData'  => ['name' => Str::title($name)],
        'perPage'    => $perPage,
    ]);
}
}
