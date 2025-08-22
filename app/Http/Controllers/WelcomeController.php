<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\AnimeLink;
use App\Models\Slider;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function index()
    {
        $animeLinks = AnimeLink::with('batches')
            ->whereNotNull('mal_id')
            ->orderByDesc('id')
            ->limit(15)
            ->get();

        $latestReleases = collect();
        $mostVisited    = collect();

        $sliders = Slider::where('is_active', true)
            ->orderBy('order')
            ->get([
                'image', 'choice', 'title', 'type', 'duration', 'duration_ms',
                'year', 'quality', 'episodes', 'description', 'mal_id'
            ]);

        $socialMedias = SocialMedia::where('is_active', true)->get();

        $animes = Anime::query()
            ->orderByRaw("FIELD(type, 'work_in_progress', 'recommendation')")
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn ($anime) => $anime->mal_id . '-' . $anime->type)
            ->map(function ($anime) {
                $anime->genres = is_string($anime->genres)
                    ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                    : (is_array($anime->genres) ? $anime->genres : []);
                return $anime;
            })
            ->values();

        // Konstruksi latest & mostVisited dari batch terbaru tiap anime
        foreach ($animeLinks as $anime) {
            $batches = $anime->batches->sortByDesc('created_at');

            foreach ($batches as $batch) {
                if (!$batch->name) {
                    continue;
                }

                $episodes = $anime->episodes;

                // Jika episodes null/kosong, ambil dari Jikan
                if (empty($episodes) && $anime->mal_id) {
                    try {
                        $response = Http::timeout(5)->get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
                        if ($response->successful()) {
                            $episodes = $response['data']['episodes'] ?? '؟';
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Gagal mengambil data Jikan untuk mal_id {$anime->mal_id}: {$e->getMessage()}");
                        $episodes = '؟';
                    }
                }

                $item = [
                    'mal_id'            => $anime->mal_id,
                    'title'             => $anime->title,
                    'title_english'     => $anime->title_english ?? $anime->title,
                    'score'             => $anime->mal_score ?? 'N/A',
                    'imdb_score'        => $anime->imdb_score ?? 'N/A',
                    'type'              => $anime->type ?? '-',
                    'episodes'          => $episodes ?? '؟',
                    'synopsis'          => $batch->name ?? '-',
                    'latest_batch_name' => $batch->name ?? '-',
                    'images' => [
                        'jpg' => [
                            'image_url' => $anime->poster ?? '/img/default-poster.jpg'
                        ]
                    ],
                    'genres' => is_string($anime->genres)
                        ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                        : [],
                    'created_at'  => $batch->created_at,

                    // >>> penting: sertakan click_count agar bisa di-sort
                    'click_count' => (int) ($anime->click_count ?? 0),
                ];

                $latestReleases->push($item);
                $mostVisited->push($item);

                // Ambil hanya batch terbaru per anime
                break;
            }
        }

        // Terbaru berdasarkan waktu batch dibuat
        $latestReleases = $latestReleases
            ->sortByDesc('created_at')
            ->values()
            ->take(5);

        // Most visited berdasarkan jumlah klik
        $mostVisited = $mostVisited
            ->sortByDesc(fn ($anime) => intval($anime['click_count'] ?? 0))
            ->values()
            ->take(5);

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
