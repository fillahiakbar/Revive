<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class WelcomeController extends Controller
{
    public function index()
    {
        // Base API dari config .env
        $baseApiUrl = rtrim(config('services.jikan.url'), '/');

        // 1. Ambil data untuk banner (anime pertama — contoh Naruto)
        $bannerResponse = Http::get($baseApiUrl . '/anime/1');
        if ($bannerResponse->failed()) {
            abort(500, 'Gagal mengambil data banner dari Jikan API');
        }
        $anime = $bannerResponse->json('data');

        // 2. Ambil data top anime untuk section "الأكثر زيارة"
        $topAnimeResponse = Http::get($baseApiUrl . '/top/anime');
        $mostVisited = $topAnimeResponse->successful()
            ? array_slice($topAnimeResponse->json('data'), 0, 4)
            : [];

        // 3. Ambil data terbaru untuk section "أحدث الإصدارات"
        $latestAnimeResponse = Http::get($baseApiUrl . '/seasons/now');
        $latestReleases = $latestAnimeResponse->successful()
            ? array_slice($latestAnimeResponse->json('data'), 0, 5)
            : [];

        // 4. Ambil data untuk section "الأعمال الحالية"
        $currentAnimeResponse = Http::get($baseApiUrl . '/top/anime');
        $currentWorks = $currentAnimeResponse->successful()
            ? array_slice($currentAnimeResponse->json('data'), 0, 4)
            : [];

        // Kirim data ke view
        return view('welcome', compact(
            'anime',
            'mostVisited',
            'latestReleases',
            'currentWorks'
        ));
    }
}
