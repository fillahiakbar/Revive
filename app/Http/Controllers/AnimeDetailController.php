<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AnimeDetailController extends Controller
{
    public function show($id)
{
    $animeResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/full");
    $recommendationResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/recommendations");

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

        usleep(500000);
    } while ($hasNext);

    if ($animeResponse->successful()) {
        $anime = $animeResponse['data'];

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
                        'episodes' => null,
                        'score' => null,
                        'duration' => null,
                    ];
                }
                if (count($similarAnime) >= 12) break;
            }
        }

        // Tambahan untuk ambil link dari database
        $downloadLinks = \App\Models\DownloadLink::where('mal_id', $anime['mal_id'])->get();

        return view('anime.show', compact('anime', 'allEpisodes', 'similarAnime', 'downloadLinks'));
    }

    abort(404, 'Anime not found');
}

}
