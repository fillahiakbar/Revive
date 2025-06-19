<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AnimeController extends Controller
{
    private $jikanApiUrl = 'https://api.jikan.moe/v4/';

    public function index()
    {
        return view('home');
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $page = $request->get('page', 1);
            
            if (empty($query)) {
                return view('anime.search', [
                    'animeList' => collect([]),
                    'query' => $query,
                    'pagination' => []
                ]);
            }

            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->get($this->jikanApiUrl . 'anime', [
                    'q' => $query,
                    'page' => $page,
                    'limit' => 25,
                    'sfw' => true
                ]);

            if (!$response->successful()) {
                Log::error('Failed to search anime from Jikan API', [
                    'query' => $query,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return view('anime.search', [
                    'animeList' => collect([]),
                    'query' => $query,
                    'error' => 'Failed to search anime. Please try again later.'
                ]);
            }

            $data = $response->json();
            $animeList = collect($data['data'] ?? [])->filter(function($anime) {
                return $this->isAnimeAppropriate($anime);
            });
            $pagination = $data['pagination'] ?? [];

            return view('anime.search', compact('animeList', 'query', 'pagination'));

        } catch (\Exception $e) {
            Log::error('Error in search method', [
                'query' => $request->get('q', ''),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('anime.search', [
                'animeList' => collect([]),
                'query' => $request->get('q', ''),
                'error' => 'An error occurred while searching anime.'
            ]);
        }
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