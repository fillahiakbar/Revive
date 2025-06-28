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
        $animeLinks = AnimeLink::with(['batches'])->get();
        $latestReleases = [];
        $mostVisited = [];
        $currentWorks = [];
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $socialMedias = SocialMedia::where('is_active', true)->get();

        foreach ($animeLinks as $anime) {
            // Ambil data dari Jikan API
            $apiResponse = Http::get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
            if (!$apiResponse->ok() || !isset($apiResponse['data'])) {
                continue;
            }

            $api = $apiResponse['data'];
            $batches = $anime->batches->sortByDesc('created_at');

            foreach ($batches as $batch) {
                $latestReleases[] = [
                    'mal_id'             => $anime->mal_id,
                    'title'              => $anime->title,
                    'score'              => $api['score'] ?? null,
                    'type'               => $api['type'] ?? '-',
                    'episodes'           => $api['episodes'] ?? null,
                    'synopsis'           => $batch->name ?? '-',
                    'latest_batch_name'  => $batch->name ?? '-',
                    'images'             => $api['images'] ?? [],
                    'genres'             => $api['genres'] ?? [],
                    'created_at'         => $batch->created_at,
                ];
            }

            // Tambahkan ke current works jika sedang tayang
            if ($api['airing'] ?? false) {
                $currentWorks[] = end($latestReleases);
            }

            // Tambahkan ke most visited (sementara)
            $mostVisited[] = end($latestReleases);
        }

        // Urutkan berdasarkan tanggal batch terbaru (ambil 5 saja)
        $latestReleases = collect($latestReleases)
            ->sortByDesc('created_at')
            ->take(5)
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
