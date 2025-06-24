<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AnimeLink; // pastikan model AnimeLink sesuai
use Illuminate\Support\Collection;

class AnimeListController extends Controller
{
     public function list(Request $request)
    {
        $letter = strtoupper($request->query('letter', 'A'));
        $page = $request->query('page', 1);
        $perPage = 24;

        // Ambil semua data dari tabel anime_links
        $query = AnimeLink::with('types');

        // Filter berdasarkan huruf
        if ($letter !== 'ALL') {
            if ($letter === '0-9') {
                $query->whereRaw("LEFT(title, 1) REGEXP '^[0-9]'");
            } else {
                $query->whereRaw("LEFT(title, 1) = ?", [$letter]);
            }
        }

        // Urutkan berdasarkan title A-Z
        $query->orderBy('title', 'asc');

        // Ambil total data
        $total = $query->count();

        // Ambil hasil untuk halaman tertentu
        $animes = $query->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        // Ambil data API Jikan untuk melengkapi gambar & title
        $enhanced = $animes->map(function ($anime) {
            $malId = $anime->mal_id;

            $jikan = cache()->remember("jikan_mal_{$malId}", 3600, function () use ($malId) {
                $res = Http::get("https://api.jikan.moe/v4/anime/{$malId}");
                return $res->successful() ? $res->json('data') : null;
            });

            return [
                'mal_id'    => $anime->mal_id,
                'title'     => $jikan['title'] ?? $anime->title,
                'images'    => $jikan['images'] ?? [],
                'types' => $anime->types->pluck('name')->toArray(),
                'episodes'  => $anime->episodes,
                'duration'  => $anime->duration,
                // Tambahkan field tambahan dari DB jika ada
            ];
        });

        // Buat pagination
        $paginatedAnimes = new LengthAwarePaginator(
            $enhanced,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

        return view('anime.list', [
            'animes' => $paginatedAnimes,
            'letter' => $letter
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

        $hasJapaneseStudio = collect($anime['studios'] ?? [])->pluck('name')->filter()->isNotEmpty();
        return $hasJapaneseStudio;
    }


}
