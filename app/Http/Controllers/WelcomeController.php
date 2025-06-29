<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use App\Models\Slider;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class WelcomeController extends Controller
{
    public function index()
    {
        $animeLinks = AnimeLink::with(['batches'])->get();

        $latestReleases = collect();
        $mostVisited = collect();
        $currentWorks = collect();
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $socialMedias = SocialMedia::where('is_active', true)->get();

        foreach ($animeLinks as $anime) {
            try {
                $response = Http::timeout(10)
                    ->withoutVerifying() // Hanya untuk LOCAL development!
                    ->get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");

                if (!$response->ok() || !isset($response['data'])) {
                    continue;
                }

                $api = $response['data'];
                $batches = $anime->batches->sortByDesc('created_at');

                foreach ($batches as $batch) {
                    $item = [
                        'mal_id'             => $anime->mal_id,
                        'title'              => $anime->title,
                        'title_english'      => $api['title_english'] ?? null,
                        'score'              => $api['score'] ?? null,
                        'type'               => $api['type'] ?? '-',
                        'episodes'           => $api['episodes'] ?? null,
                        'synopsis'           => $batch->name ?? '-',
                        'latest_batch_name'  => $batch->name ?? '-',
                        'images'             => $api['images'] ?? [],
                        'genres'             => $api['genres'] ?? [],
                        'created_at'         => $batch->created_at,
                    ];

                    $latestReleases->push($item);
                    $mostVisited->push($item);

                    if ($api['airing'] ?? false) {
                        $currentWorks->push($item);
                    }

                    break; // ambil 1 batch terbaru saja per anime
                }
            } catch (\Illuminate\Http\Client\RequestException $e) {
                Log::error("Jikan API request failed: " . $e->getMessage());
                continue;
            }
        }

        // Urutkan & potong data latest
        $latestReleases = $latestReleases
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('welcome', [
            'latestReleases' => $latestReleases,
            'mostVisited'    => $mostVisited,
            'currentWorks'   => $currentWorks,
            'sliders'        => $sliders,
            'socialMedias'   => $socialMedias,
        ]);
    }

    public function about()    { return view('about'); }
    public function terms()    { return view('terms'); }
    public function cookies()  { return view('cookies'); }
    public function privacy()  { return view('privacy'); }
}
