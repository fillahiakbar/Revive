<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GenreController extends Controller

{
 public function genres(Request $request)
    {
        try {
            $letter = $request->get('letter', 'ALL');
            $page = $request->get('page', 1);
            
            // Use caching to avoid repeated API calls
            $allGenres = Cache::remember('all_anime_genres', now()->addHours(24), function () {
                $response = Http::timeout(30)
                    ->retry(3, 1000)
                    ->get($this->jikanApiUrl . 'genres/anime');

                if (!$response->successful()) {
                    Log::error('Failed to fetch genres from Jikan API', [
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    return collect([]);
                }

                $genresData = $response->json();
                return collect($genresData['data'] ?? []);
            });

            if ($allGenres->isEmpty()) {
                return view('anime.genres', [
                    'genres' => collect([]),
                    'letter' => $letter,
                    'error' => 'Failed to load genres. Please try again later.'
                ]);
            }

            // Filter out explicit genres
            $safeGenres = $allGenres->filter(function($genre) {
                $explicitGenres = ['hentai', 'ecchi', 'erotica'];
                return !in_array(strtolower($genre['name']), $explicitGenres);
            });

            // Filter genres based on selected letter
            $filteredGenres = $this->filterGenresByLetter($safeGenres, $letter);

            // Add placeholder counts
            $filteredGenres = $filteredGenres->map(function($genre) {
                $genre['count'] = rand(10, 500);
                return $genre;
            });

            // Sort genres alphabetically
            $filteredGenres = $filteredGenres->sortBy('name');

            // Handle pagination
            $perPage = 100;
            $currentPage = $page;
            $offset = ($currentPage - 1) * $perPage;
            $total = $filteredGenres->count();
            
            $paginatedGenres = $filteredGenres->slice($offset, $perPage)->values();

            return view('anime.genres', [
                'genres' => $paginatedGenres,
                'letter' => $letter,
                'currentPage' => $currentPage,
                'total' => $total,
                'perPage' => $perPage,
                'hasMorePages' => $total > ($currentPage * $perPage)
            ]);

        } catch (\Exception $e) {
            Log::error('Error in genres method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('anime.genres', [
                'genres' => collect([]),
                'letter' => $request->get('letter', 'ALL'),
                'error' => 'An error occurred while loading genres.'
            ]);
        }
    }



     public function getAllGenresWithCounts()
    {
        return Cache::remember('genres_with_counts', now()->addHours(12), function () {
            try {
                $genresResponse = Http::timeout(30)
                    ->retry(3, 1000)
                    ->get($this->jikanApiUrl . 'genres/anime');

                if (!$genresResponse->successful()) {
                    return collect([]);
                }

                $genres = collect($genresResponse->json()['data'] ?? []);

                // Filter safe genres and add placeholder counts
                $genresWithCounts = $genres
                    ->filter(function($genre) {
                        $explicitGenres = ['hentai', 'ecchi', 'erotica'];
                        return !in_array(strtolower($genre['name']), $explicitGenres);
                    })
                    ->map(function($genre) {
                        $genre['anime_count'] = rand(10, 500); // Placeholder
                        return $genre;
                    });

                return $genresWithCounts;
            } catch (\Exception $e) {
                Log::error('Error fetching genres with counts', ['error' => $e->getMessage()]);
                return collect([]);
            }
        });
    }

     private function getGenreList(): array
    {
        return Cache::remember('genre_list', now()->addHours(12), function () {
            $response = Http::get($this->jikanApiUrl . 'genres/anime');
            if ($response->failed()) return [];

            return collect($response->json('data'))
                ->filter(function($genre) {
                    $explicitGenres = ['hentai', 'ecchi', 'erotica'];
                    return !in_array(strtolower($genre['name']), $explicitGenres);
                })
                ->map(fn($g) => ['id' => $g['mal_id'], 'name' => $g['name']])
                ->sortBy('name')
                ->values()
                ->all();
        });
    }

    private function getGenreName(int $genreId): string
    {
        $genreResponse = Http::timeout(30)->get($this->jikanApiUrl . 'genres/anime');
        
        if ($genreResponse->successful()) {
            $genresData = $genreResponse->json();
            $genre = collect($genresData['data'] ?? [])->firstWhere('mal_id', $genreId);
            if ($genre) {
                return $genre['name'];
            }
        }
        
        return 'Unknown Genre';
    }


}
