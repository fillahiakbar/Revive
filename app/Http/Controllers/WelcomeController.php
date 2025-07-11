<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use App\Models\Slider;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Anime;

class WelcomeController extends Controller
{
    public function index()
    {
        $animeLinks = AnimeLink::with(['batches'])->get();

        $latestReleases = collect();
        $mostVisited = collect();
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $socialMedias = SocialMedia::where('is_active', true)->get();

        $animes = Anime::query()
        ->orderByRaw("FIELD(type, 'work_in_progress', 'recommendation')")
        ->get()
        ->unique(fn($anime) => $anime->mal_id . '-' . $anime->type)
        ->map(function ($anime) {
            $anime->genres = is_string($anime->genres)
                ? array_map(fn($g) => ['name' => trim($g)], explode(',', $anime->genres))
                : [];
            return $anime;
        })
        ->values();

        foreach ($animeLinks as $anime) {
            try {
                $jikanResponse = Http::timeout(10)
                    ->withoutVerifying()
                    ->get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");

                if (!$jikanResponse->ok() || !isset($jikanResponse['data'])) {
                    continue;
                }

                $api = $jikanResponse['data'];
                $batches = $anime->batches->sortByDesc('created_at');

                $omdbScore = null;
                try {
                    $omdbResponse = Http::timeout(10)
                        ->get(config('services.omdb.url'), [
                            'apikey' => config('services.omdb.key'),
                            't'      => $anime->title,
                        ]);

                    if ($omdbResponse->ok() && isset($omdbResponse['imdbRating'])) {
                        $omdbScore = $omdbResponse['imdbRating'];
                    }
                } catch (\Exception $e) {
                    Log::error("OMDb API request failed for title {$anime->title}: " . $e->getMessage());
                }

                foreach ($batches as $batch) {
                    $item = [
                        'mal_id'             => $anime->mal_id,
                        'title'              => $anime->title,
                        'title_english'      => $api['title_english'] ?? null,
                        'score'              => $api['score'] ?? null,
                        'imdb_score'         => $omdbScore,
                        'type'               => $api['type'] ?? '-',
                        'episodes'           => $api['episodes'] ?? null,
                        'synopsis'           => $batch->name ?? '-',
                        'latest_batch_name'  => $batch->name ?? '-',
                        'images' => [
            'jpg' => [
                'image_url' => $anime->poster ?: ($api['images']['jpg']['image_url'] ?? null),
            ]
        ],
                        'genres'             => $api['genres'] ?? [],
                        'created_at'         => $batch->created_at,
                    ];

                    $latestReleases->push($item);
                    $mostVisited->push($item);

                    break; // Ambil hanya 1 batch terbaru
                }
            } catch (\Illuminate\Http\Client\RequestException $e) {
                Log::error("Jikan API request failed: " . $e->getMessage());
                continue;
            }
        }

        // Sort & limit
        $latestReleases = $latestReleases
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('welcome', [
            'animes'         => $animes,  
            'latestReleases' => $latestReleases,
            'mostVisited'    => $mostVisited,
            'sliders'        => $sliders,
            'socialMedias'   => $socialMedias,
        ]);
    }

    public function about()   { return view('about'); }
    public function terms()   { return view('terms'); }
    public function cookies() { return view('cookies'); }
    public function privacy() { return view('privacy'); }
}
