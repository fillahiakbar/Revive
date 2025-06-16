<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class AnimeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function show($id)
    {
        $animeResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/full");
        $episodesResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/episodes");

        if ($animeResponse->successful()) {
            $anime = $animeResponse['data'];
            $episodes = $episodesResponse->successful() ? $episodesResponse['data'] : [];

            return view('anime.show', compact('anime', 'episodes'));
        }

        abort(404, 'Anime not found');
    }

   public function list(Request $request)
{
    $letter = strtoupper($request->query('letter', 'A'));
    $page = $request->query('page', 1);
    $baseUrl = rtrim(config('services.jikan.url'), '/');

    $query = match ($letter) {
        'ALL' => '',
        '0-9' => '1',
        default => $letter
    };

    $response = Http::get("{$baseUrl}/anime", [
        'q' => $query,
        'page' => $page,
        'limit' => 24,
        'order_by' => 'title',
        'sort' => 'asc',
    ]);

    if (!$response->successful()) {
        return view('anime.list', [
            'animes' => collect(),
            'letter' => $letter
        ]);
    }

    $data = $response->json();
    $animeData = $data['data'] ?? [];

    // Filter: aman, bukan hentai, studio Jepang
    $filtered = collect($animeData)->filter(function ($anime) {
        $rating = $anime['rating'] ?? '';
        $isSafe = !in_array($rating, ['R+', 'Rx']);

        $isJapanese = collect($anime['studios'] ?? [])
            ->pluck('name')->filter()->isNotEmpty();

        $genres = collect($anime['genres'] ?? [])
            ->pluck('name')->map(fn($g) => strtolower($g));

        $isNotHentai = !$genres->contains('hentai');

        return $isSafe && $isJapanese && $isNotHentai;
    })
    // Hindari judul duplikat
    ->unique(fn($anime) => strtolower(trim($anime['title'])));

    // Paginate hasil
    $perPage = 24;
    $total = $data['pagination']['items']['total'] ?? count($filtered);

    $animes = new \Illuminate\Pagination\LengthAwarePaginator(
        $filtered->values(),
        $total,
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('anime.list', compact('animes', 'letter'));
}


public function genreMulti(Request $request)
{
    $baseUrl = rtrim(config('services.jikan.url'), '/');

    // Ambil semua filter dari form
    $searchQuery = $request->query('q'); // 🟩 Nama anime
    $status = $request->query('status'); // 🟨 Status: complete, airing, all
    $types = $request->query('types', []); // 🟥 Jenis format
    $sort = $request->query('sort', 'title_asc'); // 🟪 Urutan
    $genreIds = $request->query('genres', []); // 🟦 Genre list

    $page = $request->query('page', 1);

    // Query dasar ke Jikan API
    $queryParams = [
        'page' => $page,
        'limit' => 24,
    ];

    // Apply q (search)
    if ($searchQuery) {
        $queryParams['q'] = $searchQuery;
    }

    // Apply status
    if ($status === 'airing') {
        $queryParams['status'] = 'airing';
    } elseif ($status === 'complete') {
        $queryParams['status'] = 'complete';
    }

    // Apply sort
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

    // Apply genres
    if (!empty($genreIds)) {
        $queryParams['genres'] = implode(',', $genreIds);
    }

    // Hanya satu "type" yang bisa diterima oleh API, kita ambil salah satu saja
    if (!empty($types)) {
        $queryParams['type'] = $types[0]; // ambil tipe pertama saja
    }

    $response = Http::get("{$baseUrl}/anime", $queryParams);

    $animeData = $response->successful() ? $response->json('data') : [];

    // Filter aman & studio Jepang
    $filtered = collect($animeData)->filter(function ($anime) {
        $rating = $anime['rating'] ?? '';
        $isSafe = !in_array($rating, ['R+', 'Rx']);

        $isJapanese = collect($anime['studios'] ?? [])->pluck('name')->isNotEmpty();

        $genres = collect($anime['genres'] ?? [])->pluck('name')->map(fn($g) => strtolower($g));
        $isNotHentai = !$genres->contains('hentai');

        return $isSafe && $isJapanese && $isNotHentai;
    })->unique(fn($anime) => strtolower(trim($anime['title'])))->values();

    // Handle Infinite Scroll (return JSON jika diminta)
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


private function getGenreList()
{
    return Cache::remember('genre_list', now()->addHours(12), function () {
        $response = Http::get('https://api.jikan.moe/v4/genres/anime');
        if ($response->failed()) return [];

        return collect($response->json('data'))
            ->map(fn($g) => ['id' => $g['mal_id'], 'name' => $g['name']])
            ->sortBy('name')
            ->values()
            ->all();
    });
}


}
