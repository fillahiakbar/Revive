<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use Illuminate\Support\Facades\Http;
use App\Models\Slider;
use App\Models\SocialMedia;

class WelcomeController extends Controller
{
    public function index()
    {
        $animeLinks = AnimeLink::with('batches')->get();
        $latestReleases = [];
        $mostVisited = [];
        $currentWorks = [];
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $socialMedias = SocialMedia::where('is_active', true)->get();

        foreach ($animeLinks as $anime) {
            $apiResponse = Http::get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
            $firstBatch = $anime->batches->first(); // assuming hasMany

            if ($apiResponse->ok() && isset($apiResponse['data'])) {
                $api = $apiResponse['data'];

                $latestReleases[] = [
                    'mal_id' => $anime->mal_id,
                    'title' => $anime->title,
                    'score' => $api['score'] ?? null,
                    'type' => $api['type'] ?? '-',
                    'episodes' => $api['episodes'] ?? null,
                    'synopsis' => $firstBatch?->name ?? '-',
                    'latest_batch_name' => $anime->batches->sortByDesc('created_at')->first()?->name,
                    'images' => $api['images'],
                    'genres' => $api['genres'] ?? [],
                    'created_at' => optional($firstBatch)->created_at, // penting untuk sorting
                ];

                if ($api['airing'] ?? false) {
                    $currentWorks[] = end($latestReleases);
                }

                $mostVisited[] = end($latestReleases);
            }
        }

        // Sort berdasarkan tanggal upload batch terbaru
        $latestReleases = collect($latestReleases)
            ->sortByDesc('created_at')
            ->values()
            ->all();

        return view('welcome', [
            'latestReleases' => $latestReleases,
            'mostVisited' => $mostVisited,
            'currentWorks' => $currentWorks,
            'sliders' => $sliders,
            'socialMedias' => $socialMedias,
        ]);
    }

    public function about()
    {
        return view('about');
    }

    public function terms()
    {
        return view('terms');
    }

    public function cookies()
    {
        return view('cookies');
    }

    public function privacy()
    {
        return view('privacy');
    }
}
