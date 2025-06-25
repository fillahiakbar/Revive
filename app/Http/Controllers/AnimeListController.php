<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AnimeLink; 

class AnimeListController extends Controller
{
    public function list(Request $request)
    {
        $letter = strtoupper($request->query('letter', 'ALL'));
        $page = (int) $request->query('page', 1);
        $perPage = 24;

        // Ambil query builder dengan relasi
        $query = AnimeLink::with('types');

        // Filter huruf awal judul
        if ($letter !== 'ALL') {
            if ($letter === '0-9') {
                $query->whereRaw("LEFT(title, 1) REGEXP '^[0-9]'");
            } else {
                $query->whereRaw("LEFT(title, 1) = ?", [$letter]);
            }
        }

        // Total data sebelum paginasi
        $total = $query->count();

        // Data per halaman
        $animes = $query->orderBy('title', 'asc')
                        ->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        // Gabungkan data lokal dan API
        $enhanced = $animes->map(function ($anime) {
            $malId = $anime->mal_id;

            // Ambil dari API atau cache
            $apiData = cache()->remember("jikan_mal_{$malId}", 3600, function () use ($malId) {
                $response = Http::get("https://api.jikan.moe/v4/anime/{$malId}");
                if ($response->successful()) {
                    $data = $response->json('data');
                    return $this->isAnimeAppropriate($data) ? $data : null;
                }
                return null;
            });

            return [
                'mal_id'        => $anime->mal_id,
                'local_title'   => $anime->title,
                'title'         => $apiData['title'] ?? $anime->title,
                'images'        => $apiData['images'] ?? [],
                'duration'      => $apiData['duration'] ?? null,
                'score'         => $apiData['score'] ?? null,
                'types'         => $anime->types->pluck('name')->toArray(),
                'episodes'      => $anime->episodes,
            ];
        })->filter(); // hilangkan null/unsafe

        // Buat pagination manual
        $paginated = new LengthAwarePaginator(
            $enhanced,
            $total,
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => $request->query(),
            ]
        );

        return view('anime.list', [
            'animes' => $paginated,
            'letter' => $letter,
        ]);
    }

    private function isAnimeAppropriate($anime): bool
    {
        $rating = strtolower($anime['rating'] ?? '');
        $unsafeRatings = ['rx', 'r+'];
        foreach ($unsafeRatings as $r) {
            if (str_contains($rating, $r)) return false;
        }

        $genres = collect($anime['genres'] ?? [])->pluck('name')->map(fn($g) => strtolower($g));
        $explicitGenres = ['hentai', 'ecchi', 'erotica'];
        if ($genres->intersect($explicitGenres)->isNotEmpty()) return false;

        $studios = collect($anime['studios'] ?? [])->pluck('name');
        if ($studios->isEmpty()) return false;

        return true;
    }
}
