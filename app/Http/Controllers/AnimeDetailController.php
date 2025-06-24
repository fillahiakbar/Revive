<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use Illuminate\Support\Facades\Http;

class AnimeDetailController extends Controller
{
    public function show($id)
    {
        // Ambil data AnimeLink berdasarkan mal_id, termasuk relasi types dan batches.batchLinks
        $animeLink = AnimeLink::where('mal_id', $id)
    ->with([
        'types',
        'batches' => fn ($q) => $q->has('batchLinks'), // hanya ambil batch yang punya link
        'batches.batchLinks'
    ])
    ->first();

        $downloadLinks = collect();
        $animeData = [];
        $fromDatabase = false;

        // Fetch data dari Jikan API
        $apiData = [];
        $apiResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/full");

        if ($apiResponse->successful()) {
            $data = $apiResponse['data'];
            $apiData = [
                'title' => $data['title'] ?? null,
                'title_japanese' => $data['title_japanese'] ?? null,
                'poster' => $data['images']['jpg']['large_image_url'] ?? null,
                'synopsis' => $data['synopsis'] ?? null,
                'season' => $data['season'] ?? null,
                'year' => $data['year'] ?? null,
                'type' => $data['type'] ?? null,
                'status' => $data['status'] ?? null,
                'studios' => $data['studios'] ?? [],
                'genres' => collect($data['genres'] ?? [])->pluck('name')->toArray(),
                'episodes' => $data['episodes'] ?? null,
                'duration' => $data['duration'] ?? null,
                'aired' => $data['aired'] ?? [],
                'score' => $data['score'] ?? null,
            ];
        } else {
            abort(404, 'Anime not found via API');
        }

        // Jika anime ditemukan di database lokal
        if ($animeLink) {
            // Force refresh untuk relasi batches dan batchLinks agar data selalu terbaru
            $animeLink = $animeLink->fresh(['types', 'batches.batchLinks']);
            $fromDatabase = true;

            // Gunakan data lokal jika ada, fallback ke API
            $animeData = [
                'title' => $animeLink->title ?? $apiData['title'],
                'title_japanese' => $apiData['title_japanese'],
                'poster' => $animeLink->poster ?? $apiData['poster'],
                'synopsis' => $animeLink->synopsis ?? $apiData['synopsis'],
                'season' => $animeLink->season ?? $apiData['season'],
                'year' => $animeLink->year ?? $apiData['year'],
                'types' => $animeLink->types->pluck('name')->toArray() ?: [$apiData['type']],
                'status' => $apiData['status'],
                'studios' => $apiData['studios'],
                'genres' => $apiData['genres'],
                'episodes' => $apiData['episodes'],
                'duration' => $apiData['duration'],
                'aired' => $apiData['aired'],
                'score' => $apiData['score'],
            ];

            // FlatMap semua batchLinks
            $downloadLinks = $animeLink->batches->flatMap->batchLinks;
        } else {
            // Jika tidak ditemukan di database, gunakan full dari API
            $animeData = $apiData;
        }

        // Ambil rekomendasi dari API
        $recommendations = [];
        $recResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/recommendations");

        if ($recResponse->successful()) {
            foreach ($recResponse['data'] as $item) {
                if (isset($item['entry'])) {
                    $recommendations[] = [
                        'mal_id' => $item['entry']['mal_id'] ?? null,
                        'title' => $item['entry']['title'] ?? 'Unknown',
                        'type' => $item['entry']['type'] ?? 'Unknown',
                        'images' => $item['entry']['images'] ?? [],
                    ];
                }

                if (count($recommendations) >= 12) break;
            }
        }

        // Render view dengan semua data
        return view('anime.show', [
            'animeLink' => $animeLink,
            'anime' => $animeData,
            'downloadLinks' => $downloadLinks,
            'similarAnime' => $recommendations,
            'fromDatabase' => $fromDatabase,
        ]);
    }
}
