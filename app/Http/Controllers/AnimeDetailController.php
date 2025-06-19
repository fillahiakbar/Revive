<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnimeDetailController extends Controller
{
    public function show($id)
    {
        $animeResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/full");
        $recommendationResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/recommendations");

        $allEpisodes = [];
        $page = 1;

        // Fetch all episodes paginated
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

            usleep(500000); // 500ms delay to avoid rate limit
        } while ($hasNext);

        // Success: show page
        if ($animeResponse->successful()) {
            $anime = $animeResponse['data'];

            // Get download links from Filament-managed table
            $downloadLinks = Download::where('mal_id', $anime['mal_id'])->get();

            // Recommendations
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
                        ];
                    }
                    if (count($similarAnime) >= 12) break;
                }
            }

            return view('anime.show', compact('anime', 'allEpisodes', 'similarAnime', 'downloadLinks'));
        }

        abort(404, 'Anime not found');
    }
}
