<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use Illuminate\Support\Facades\Http;
use App\Models\Slider;

class WelcomeController extends Controller
{
    public function index()
    {
        $animeLinks = AnimeLink::all();
        $latestReleases = [];
        $mostVisited = [];
        $currentWorks = [];
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();

        foreach ($animeLinks as $anime) {
            $apiResponse = Http::get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");

            if ($apiResponse->ok() && isset($apiResponse['data'])) {
                $api = $apiResponse['data'];

                $latestReleases[] = [
                    'mal_id' => $anime->mal_id,
                    'title' => $anime->title,
                    'score' => $api['score'] ?? null,
                    'type' => $api['type'] ?? '-',
                    'episodes' => $api['episodes'] ?? null,
                    'synopsis' => $api['synopsis'] ?? '-',
                    'images' => $api['images'],
                    'genres' => $api['genres'] ?? [],
                ];

                // Tambahkan ke currentWorks jika airing
                if ($api['airing'] ?? false) {
                    $currentWorks[] = end($latestReleases);
                }

                // Tambahkan ke mostVisited secara dummy (contoh)
                $mostVisited[] = end($latestReleases);
            }
        }

        return view('welcome', [
            'latestReleases' => $latestReleases,
            'mostVisited' => $mostVisited,
            'currentWorks' => $currentWorks,
            'sliders' => $sliders,
        ]);
    }




    public function about()
{
    return view('about');
}
}
