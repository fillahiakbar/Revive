<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AnimeListController extends Controller
{
    public function list(Request $request)
    {
        $letter = strtoupper($request->query('letter', 'A'));
        $page = $request->query('page', 1);
        $baseUrl = rtrim(config('services.jikan.url', 'https://api.jikan.moe/v4'), '/');

        // Ambil lebih banyak data dari API untuk memastikan setelah filter masih ada 24 item
        $maxPages = 20;
        $allAnimes = collect();
        
        for ($apiPage = 1; $apiPage <= $maxPages; $apiPage++) {
            $query = match ($letter) {
                'ALL' => '',
                '0-9' => '1',
                default => $letter
            };

            $response = Http::get("{$baseUrl}/anime", [
                'q' => $query,
                'page' => $apiPage,
                'limit' => 25,
                'order_by' => 'title',
                'sort' => 'asc',
                'sfw' => true
            ]);

            if (!$response->successful()) {
                break;
            }

            $data = $response->json();
            $animeData = $data['data'] ?? [];

            if (empty($animeData)) {
                break;
            }

            // Filter: aman, bukan hentai, studio Jepang
            $filtered = collect($animeData)->filter(function ($anime) {
                return $this->isAnimeAppropriate($anime);
            });

            $allAnimes = $allAnimes->merge($filtered);

            // Rate limiting
            usleep(250000); // 0.25 detik delay
        }

        // Remove duplicates berdasarkan title
        $uniqueAnimes = $allAnimes->unique(function ($anime) {
            return strtolower(trim($anime['title']));
        })->values();

        // Manual pagination
        $perPage = 24;
        $currentPage = $page;
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedAnimes = $uniqueAnimes->slice($offset, $perPage)->values();
        $total = $uniqueAnimes->count();

        // Jika data kurang dari 24 pada halaman pertama, coba ambil lebih banyak
        if ($paginatedAnimes->count() < 24 && $currentPage == 1 && $total < 24) {
            $this->addFallbackAnimes($uniqueAnimes, $baseUrl);
            $paginatedAnimes = $uniqueAnimes->slice($offset, $perPage)->values();
            $total = $uniqueAnimes->count();
        }

        // Create pagination instance
        $animes = new LengthAwarePaginator(
            $paginatedAnimes,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

        return view('anime.list', compact('animes', 'letter'));
    }

    private function isAnimeAppropriate($anime): bool
{
    $rating = strtolower($anime['rating'] ?? '');
    $unsafeRatings = ['rx', 'r+'];

    foreach ($unsafeRatings as $unsafeRating) {
        if (str_contains($rating, $unsafeRating)) {
            return false;
        }
    }

    $genres = collect($anime['genres'] ?? [])->pluck('name')->map(fn($g) => strtolower($g));
    $explicitGenres = ['hentai', 'ecchi', 'erotica'];

    if ($genres->intersect($explicitGenres)->isNotEmpty()) {
        return false;
    }

    $hasJapaneseStudio = collect($anime['studios'] ?? [])->pluck('name')->filter()->isNotEmpty();

    return $hasJapaneseStudio;
}

}
