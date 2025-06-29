<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimeLink;
use Illuminate\Support\Facades\Http;

class AnimeOngoingController extends Controller
{
    private $jikanApiUrl = 'https://api.jikan.moe/v4/anime/';

    public function index(Request $request)
    {
        // Ambil semua anime dari database
        $localAnimes = AnimeLink::with(['types', 'batches.batchLinks'])->get();

        $ongoingAnimes = [];

        foreach ($localAnimes as $anime) {
            $malId = $anime->mal_id;
            if (!$malId) continue;

            // Ambil data API dari cache atau langsung
            $apiData = cache()->remember("jikan_mal_{$malId}", 3600, function () use ($malId) {
                $res = Http::get("https://api.jikan.moe/v4/anime/{$malId}");
                return $res->successful() ? $res->json('data') : null;
            });

            if (!$apiData) continue;

            // Filter hanya anime yang masih airing
            if (($apiData['status'] ?? '') !== 'Currently Airing') continue;

            // Gabungkan data API dan lokal
            $ongoingAnimes[] = [
                'mal_id'        => $anime->mal_id,
                'local_title'   => $anime->title,
                'title'         => $apiData['title'] ?? $anime->title,
                'title_english' => $apiData['title_english'] ?? null,
                'images'        => $apiData['images'] ?? [],
                'duration'      => $apiData['duration'] ?? null,
                'score'         => $apiData['score'] ?? null,
                'types' => $anime->types->map(fn($type) => [
    'name' => $type->name,
    'color' => $type->color ?? '#6b7280',
]),
                'episodes'      => $anime->episodes,
                'batches'       => $anime->batches ?? [],
            ];
        }

        return view('anime.ongoing', [
            'animes' => $ongoingAnimes,
            'pagination' => []
        ]);
    }
}
