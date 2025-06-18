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

public function show($id)
{
    // Ambil detail anime lengkap
    $animeResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/full");

    // Ambil rekomendasi (similar anime)
    $recommendationResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/recommendations");

    // Ambil semua episode
    $allEpisodes = [];
    $page = 1;

    do {
        $episodesResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/episodes", [
            'page' => $page
        ]);

        if (!$episodesResponse->successful()) {
            break;
        }

        $data = $episodesResponse->json();
        $allEpisodes = array_merge($allEpisodes, $data['data']);
        $hasNext = $data['pagination']['has_next_page'] ?? false;
        $page++;

        usleep(500000); // delay 0.5 detik (rate limit)

    } while ($hasNext);

    // Jika anime ditemukan
    if ($animeResponse->successful()) {
        $anime = $animeResponse['data'];

        // Ambil hanya 12 rekomendasi pertama (optional)
        $similarAnime = [];

        if ($recommendationResponse->successful()) {
            $recommendations = $recommendationResponse['data'];
            foreach ($recommendations as $item) {
                if (isset($item['entry'])) {
                    $similarAnime[] = [
                        'mal_id' => $item['entry']['mal_id'] ?? null,
                        'title' => $item['entry']['title'] ?? 'Unknown',
                        'type' => $item['entry']['type'] ?? 'Unknown',
                        'images' => $item['entry']['images'] ?? [],
                        'episodes' => null, // optional karena data rekomendasi tidak punya ini
                        'score' => null, // optional
                        'duration' => null, // optional
                    ];
                }
                if (count($similarAnime) >= 12) break;
            }
        }

        return view('anime.show', compact('anime', 'allEpisodes', 'similarAnime'));
    }

    // Jika gagal ambil anime
    abort(404, 'Anime not found');
}

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

    public function genreMulti(Request $request)
    {
        $baseUrl = rtrim(config('services.jikan.url', 'https://api.jikan.moe/v4'), '/');

        // Ambil semua filter dari form
        $searchQuery = $request->query('q');
        $status = $request->query('status');
        $types = $request->query('types', []);
        $sort = $request->query('sort', 'title_asc');
        $genreIds = $request->query('genres', []);
if (!is_array($genreIds)) {
    $genreIds = explode(',', $genreIds); // handle dari ?genres=1,2,3
}
        $page = $request->query('page', 1);

        // Query dasar ke Jikan API
        $queryParams = [
            'page' => $page,
            'limit' => 24,
            'sfw' => true,
        ];

        // Apply filters
        if ($searchQuery) {
            $queryParams['q'] = $searchQuery;
        }

        if ($status === 'airing') {
            $queryParams['status'] = 'airing';
        } elseif ($status === 'complete') {
            $queryParams['status'] = 'complete';
        }

        // Apply sort
        $this->applySortParams($queryParams, $sort);

        // Apply genres - filter out hentai/ecchi genres
        if (!empty($genreIds)) {
            $safeGenreIds = $this->filterSafeGenres($genreIds);
            if (!empty($safeGenreIds)) {
                $queryParams['genres'] = implode(',', $safeGenreIds);
            }
        }

        // Apply types
        if (!empty($types)) {
            $queryParams['type'] = $types[0];
        }

        $response = Http::get("{$baseUrl}/anime", $queryParams);
        $animeData = $response->successful() ? $response->json('data') : [];

        // Filter tambahan untuk keamanan
        $filtered = collect($animeData)->filter(function ($anime) {
            return $this->isAnimeAppropriate($anime);
        })->unique('mal_id')->values();

        // Handle JSON request
        if ($request->wantsJson()) {
            return response()->json($filtered);
        }

        return view('anime.genre-multi', [
            'animes' => $filtered,
            'genres' => $this->getGenreList(),
            'selected' => $genreIds ?? [],
            'selectedTypes' => $types ?? [],
            'selectedSort' => $sort ?? null,
            'selectedStatus' => $status ?? null,
            'query' => $searchQuery ?? ''
        ]);
    }

    /**
     * Display genres page with filtering
     */
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

    /**
     * Show anime by genre
     */
     public function byGenre($genre_id, Request $request)
{
    try {
        \Log::info('byGenre method called with genre_id: ' . $genre_id);

        // Ambil semua genre dan cari yang cocok
        $allGenresResponse = Http::timeout(30)->get('https://api.jikan.moe/v4/genres/anime');
        $allGenres = $allGenresResponse->successful() ? $allGenresResponse->json()['data'] : [];

        $genreData = collect($allGenres)->firstWhere('mal_id', (int) $genre_id);

        if (!$genreData) {
            \Log::warning("Genre not found for ID: $genre_id");
            abort(404, 'Genre not found');
        }

        // Ambil anime-anime berdasarkan genre
        $params = [
            'genres' => $genre_id,
            'page' => $request->get('page', 1),
            'limit' => 24,
            'order_by' => $request->get('sort', 'popularity'),
            'sort' => 'desc',
            'sfw' => true
        ];

        $animeResponse = Http::timeout(30)->get("https://api.jikan.moe/v4/anime", $params);

        if (!$animeResponse->successful()) {
            \Log::error('Anime API failed', ['params' => $params, 'status' => $animeResponse->status()]);
            $animeList = [];
            $pagination = null;
        } else {
            $animeData = $animeResponse->json();
            $animeList = collect($animeData['data'] ?? [])->filter(fn($anime) => $this->isAnimeAppropriate($anime))->values()->all();
            $pagination = $animeData['pagination'] ?? null;
        }

        return view('anime.by-genre', compact('animeList', 'genreData', 'pagination', 'allGenres', 'genre_id'));

    } catch (\Exception $e) {
        \Log::error('Error in byGenre method', [
            'genre_id' => $genre_id,
            'error' => $e->getMessage(),
        ]);
        return back()->with('error', 'Failed to load anime data: ' . $e->getMessage());
    }
}

    /**
     * Search anime
     */
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

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Check if anime is appropriate (safe content)
     */
    private function isAnimeAppropriate($anime): bool
    {
        // Check rating
        $rating = strtolower($anime['rating'] ?? '');
        $unsafeRatings = ['rx', 'r+'];
        
        foreach ($unsafeRatings as $unsafeRating) {
            if (str_contains($rating, $unsafeRating)) {
                return false;
            }
        }

        // Check genres
        $genres = collect($anime['genres'] ?? [])->pluck('name')->map(fn($g) => strtolower($g));
        $explicitGenres = ['hentai', 'ecchi', 'erotica'];
        
        if ($genres->intersect($explicitGenres)->isNotEmpty()) {
            return false;
        }

        // Check if has Japanese studio (optional filter)
        $hasJapaneseStudio = collect($anime['studios'] ?? [])->pluck('name')->filter()->isNotEmpty();

        return $hasJapaneseStudio;
    }

    /**
     * Add fallback animes when filtered results are insufficient
     */
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

    /**
     * Apply sort parameters to query
     */
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

    /**
     * Filter out explicit genres from genre IDs
     */
    private function filterSafeGenres(array $genreIds): array
    {
        $explicitGenreIds = [9, 12]; // 9 = Ecchi, 12 = Hentai (MAL genre IDs)
        return array_filter($genreIds, function($genreId) use ($explicitGenreIds) {
            return !in_array($genreId, $explicitGenreIds);
        });
    }

    /**
     * Get cached genre list
     */
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

    /**
     * Filter genres by letter
     */
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

    /**
     * Get genre name by ID
     */
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

    
   

    /**
     * Alternative method to get comprehensive genre data with real counts
     */
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

    
}