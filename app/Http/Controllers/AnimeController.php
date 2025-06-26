<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\AnimeLink;   

class AnimeController extends Controller
{
    private $jikanApiUrl = 'https://api.jikan.moe/v4/';

    public function index()
    {
        return view('home');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return view('anime.search', [
                'animeList' => collect([]),
                'query' => $query,
                'pagination' => [],
            ]);
        }

        try {
            $result = [];

            // Ambil anime dari database lokal yang judulnya cocok
            $localAnimes = AnimeLink::with(['types', 'batches.batchLinks'])
                ->where('title', 'like', '%' . $query . '%')
                ->get();

            foreach ($localAnimes as $anime) {
                if (!$anime->mal_id) continue;

                $api = Http::timeout(10)->get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");

                if ($api->successful()) {
                    $data = $api->json('data');

                    $result[] = array_merge($data, [
                        'local_title' => $anime->title,
                        'title_english' => $data['title_english'] ?? null,
                        'type' => $anime->types->first()->name ?? null,
                        'batches' => $anime->batches ?? [],
                        'episodes' => $anime->episodes,
                    ]);
                }
            }

            // Fallback ke pencarian API jika tidak ada dari lokal
            if (empty($result)) {
                $apiSearch = Http::get("https://api.jikan.moe/v4/anime", [
                    'q' => $query,
                    'limit' => 10
                ]);

                if ($apiSearch->successful()) {
                    foreach ($apiSearch['data'] as $anime) {
                        $result[] = [
                            'mal_id' => $anime['mal_id'],
                            'title' => $anime['title'],
                            'title_english' => $anime['title_english'] ?? null,
                            'images' => $anime['images'],
                            'score' => $anime['score'] ?? null,
                            'duration' => $anime['duration'] ?? null,
                            'type' => $anime['type'] ?? null,
                            'local_title' => $anime['title'],
                        ];
                    }
                }
            }

            return view('anime.search', [
                'animeList' => collect($result),
                'query' => $query,
                'pagination' => [],
            ]);

        } catch (\Exception $e) {
            Log::error('Search Error', [
                'query' => $query,
                'message' => $e->getMessage(),
            ]);

            return view('anime.search', [
                'animeList' => collect([]),
                'query' => $query,
                'error' => 'Terjadi kesalahan saat pencarian. Silakan coba lagi.',
            ]);
        }
    }

    private function isAnimeAppropriate(array $anime): bool
    {
        $explicitGenres = ['Hentai', 'Ecchi'];
        if (!isset($anime['genres'])) {
            return true;
        }

        foreach ($anime['genres'] as $genre) {
            if (in_array($genre['name'], $explicitGenres)) {
                return false;
            }
        }

        return true;
    }

    private function addFallbackAnimes(Collection &$uniqueAnimes, string $baseUrl): void
    {
        $fallbackResponse = Http::get("{$baseUrl}/anime", [
            'page' => 1,
            'limit' => 25,
            'order_by' => 'popularity',
            'sort' => 'asc',
            'status' => 'complete',
            'sfw' => true
        ]);

        if ($fallbackResponse->successful()) {
            $fallbackData = $fallbackResponse->json()['data'] ?? [];
            $fallbackFiltered = collect($fallbackData)->filter(function ($anime) {
                return $this->isAnimeAppropriate($anime);
            });

            $uniqueAnimes = $uniqueAnimes->merge($fallbackFiltered)
                ->unique(function ($anime) {
                    return strtolower(trim($anime['title']));
                })->values();
        }
    }

    private function applySortParams(array &$queryParams, string $sort): void
    {
        switch ($sort) {
            case 'title_asc':
                $queryParams['order_by'] = 'title';
                $queryParams['sort'] = 'asc';
                break;
            case 'title_desc':
                $queryParams['order_by'] = 'title';
                $queryParams['sort'] = 'desc';
                break;
            case 'episodes':
                $queryParams['order_by'] = 'episodes';
                $queryParams['sort'] = 'desc';
                break;
            case 'updated':
                $queryParams['order_by'] = 'updated_at';
                $queryParams['sort'] = 'desc';
                break;
            case 'recent':
                $queryParams['order_by'] = 'start_date';
                $queryParams['sort'] = 'desc';
                break;
            default:
                $queryParams['order_by'] = 'title';
                $queryParams['sort'] = 'asc';
        }
    }

    private function filterSafeGenres(array $genreIds): array
    {
        $explicitGenreIds = [9, 12]; // 9 = Ecchi, 12 = Hentai (MAL genre IDs)
        return array_filter($genreIds, function($genreId) use ($explicitGenreIds) {
            return !in_array($genreId, $explicitGenreIds);
        });
    }

    private function filterGenresByLetter(Collection $genres, string $letter): Collection
    {
        if ($letter === 'ALL') {
            return $genres;
        }

        if ($letter === '0-9') {
            return $genres->filter(function ($genre) {
                $firstChar = substr($genre['name'], 0, 1);
                return is_numeric($firstChar);
            });
        }

        return $genres->filter(function ($genre) use ($letter) {
            $firstChar = strtoupper(substr($genre['name'], 0, 1));
            return $firstChar === strtoupper($letter);
        });
    }
}
