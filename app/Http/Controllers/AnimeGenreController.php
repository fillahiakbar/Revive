<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AnimeLink;

class AnimeGenreController extends Controller
{
    private string $jikanApiUrl = 'https://api.jikan.moe/v4/';

    public function byGenre($genre_id, Request $request)
{
    try {
        Log::info('byGenre method called with genre_id: ' . $genre_id);

        // Ambil semua genre dari Jikan
        $allGenresResponse = Http::timeout(30)->get($this->jikanApiUrl . 'genres/anime');
        $allGenres = $allGenresResponse->successful() ? $allGenresResponse->json()['data'] : [];

        $genreData = collect($allGenres)->firstWhere('mal_id', (int) $genre_id);

        if (!$genreData) {
            Log::warning("Genre not found for ID: $genre_id");
            abort(404, 'Genre not found');
        }

        // Ambil dari database lokal berdasarkan genre_id
        $query = AnimeLink::with(['types'])
            ->whereHas('types', function ($q) use ($genre_id) {
                $q->where('mal_id', $genre_id);
            });

        $perPage = 24;
        $page = $request->get('page', 1);

        $animeLinks = $query->paginate($perPage, ['*'], 'page', $page);

        // Mapping data ke bentuk blade dan ambil title_english + score dari API
        $animeList = $animeLinks->getCollection()->map(function ($anime) {
            $titleEnglish = null;
            $score = null;
            $images = ['jpg' => ['image_url' => $anime->image_url]];

            try {
                $api = Http::timeout(10)->get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
                if ($api->successful()) {
                    $data = $api->json('data');
                    $titleEnglish = $data['title_english'] ?? null;
                    $score = $data['score'] ?? null;
                    $images = $data['images'] ?? $images;
                }
            } catch (\Exception $e) {
                Log::warning("API failed for mal_id {$anime->mal_id}: " . $e->getMessage());
            }

            return [
                'mal_id' => $anime->mal_id,
                'title' => $anime->title,
                'title_english' => $titleEnglish,
                'score' => $score,
                'images' => $images,
                'duration' => $anime->duration,
                'episodes' => $anime->episodes,
                'type' => $anime->type,
            ];
        })->values()->all();

        $pagination = [
            'current_page' => $animeLinks->currentPage(),
            'last_visible_page' => $animeLinks->lastPage(),
            'items_count' => $animeLinks->total(),
            'has_next_page' => $animeLinks->hasMorePages(),
        ];

        return view('anime.by-genre', compact('animeList', 'genreData', 'pagination', 'allGenres', 'genre_id'));

    } catch (\Exception $e) {
        Log::error('Error in byGenre method', [
            'genre_id' => $genre_id,
            'error' => $e->getMessage(),
        ]);
        return back()->with('error', 'Failed to load anime data: ' . $e->getMessage());
    }
}


    public function genreMulti(Request $request)
    {
        $searchQuery = $request->query('q');
        $types = $request->query('types', []);
        $genreIds = $request->query('genres', []);
        $sort = $request->query('sort', 'title_asc');
        $status = $request->query('status');
        $page = $request->query('page', 1);

        $query = AnimeLink::query();

        if ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%');
        }

        if (!empty($types)) {
            $query->whereIn('type', $types);
        }

        $animeLinks = $query->with('types')->paginate(24);

        // Mapping dan gabungkan dengan Jikan API
        $animes = $animeLinks->getCollection()->map(function ($anime) {
            $data = null;

            try {
                $response = Http::timeout(10)->get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
                if ($response->successful()) {
                    $data = $response->json('data');
                }
            } catch (\Exception $e) {
                Log::warning("Gagal mengambil data Jikan untuk MAL ID {$anime->mal_id}: " . $e->getMessage());
            }

            return [
                'mal_id' => $anime->mal_id,
                'title' => $anime->title,
                'local_title' => $anime->title,
                'episodes' => $data['episodes'] ?? null,
                'duration' => $data['duration'] ?? null,
                'score' => $data['score'] ?? null,
                'images' => $data['images'] ?? null,
                'image' => $data['images']['jpg']['image_url'] ?? null,
                'types' => $anime->types->map(function ($type) {
    return [
        'name' => $type->name,
        'color' => $type->color ?? '#6b7280', // default Tailwind gray-500
    ];
}),
            ];
        });

        // Buat ulang paginator dengan data baru
        $paginated = new LengthAwarePaginator(
            $animes,
            $animeLinks->total(),
            $animeLinks->perPage(),
            $animeLinks->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('anime.genre-multi', [
            'animes' => $paginated,
            'genres' => $this->getGenreList(),
            'selected' => $genreIds ?? [],
            'selectedTypes' => $types ?? [],
            'selectedSort' => $sort ?? null,
            'selectedStatus' => $status ?? null,
            'query' => $searchQuery ?? '',
            'hasMorePages' => $paginated->hasMorePages(),
        ]);
    }

    private function getGenreList(): array
    {
        return Cache::remember('genre_list', now()->addHours(12), function () {
            $response = Http::get($this->jikanApiUrl . 'genres/anime');
            if ($response->failed()) return [];

            return collect($response->json('data'))
                ->filter(fn($genre) => !in_array(strtolower($genre['name']), ['hentai', 'ecchi', 'erotica']))
                ->map(fn($g) => ['id' => $g['mal_id'], 'name' => $g['name']])
                ->sortBy('name')
                ->values()
                ->all();
        });
    }

    private function isAnimeAppropriate(array $anime): bool
    {
        $explicitGenres = ['Hentai', 'Ecchi', 'Erotica'];

        if (!isset($anime['genres'])) return true;

        foreach ($anime['genres'] as $genre) {
            if (in_array($genre['name'], $explicitGenres)) {
                return false;
            }
        }

        return true;
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
                $queryParams['order_by'] = 'popularity';
                $queryParams['sort'] = 'desc';
        }
    }
}
