<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use Illuminate\Support\Facades\Http;

class AnimeDetailController extends Controller
{
    public function show($id)
    {
        // Ambil data dari database berdasarkan mal_id
        $animeLink = AnimeLink::where('mal_id', $id)
            ->with([
                'types',
                'batches' => fn ($q) => $q->has('batchLinks'),
                'batches.batchLinks',
                'comments' => fn ($q) => $q->latest(),
                'comments.user',
            ])
            ->first();

        $downloadLinks = collect();
        $animeData = [];
        $fromDatabase = false;

        // Ambil data dari API Jikan
        $apiResponse = Http::get("https://api.jikan.moe/v4/anime/{$id}/full");

        if (!$apiResponse->successful()) {
            abort(404, 'Anime not found via API');
        }

        $data = $apiResponse['data'] ?? [];

        // Siapkan data dari API sebagai default
        $apiData = [
            'mal_id' => $data['mal_id'] ?? $id,
            'title' => $data['title'] ?? null,
            'title_english' => $data['title_english'] ?? null,
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

        // Jika data tersedia di database, gunakan sebagai prioritas
        if ($animeLink) {
            $animeLink->loadMissing(['types', 'batches.batchLinks']);
            $fromDatabase = true;

            $animeData = [
                'mal_id' => $apiData['mal_id'],
                'title' => $animeLink->title ?? $apiData['title'],
                'title_english' => $apiData['title_english'],
                'title_japanese' => $apiData['title_japanese'],
                'poster' => $animeLink->poster ?? $apiData['poster'],
                'synopsis' => $animeLink->synopsis ?? $apiData['synopsis'],
                'season' => $animeLink->season ?? $apiData['season'],
                'year' => $animeLink->year ?? $apiData['year'],
                'types' => $animeLink->types->pluck('name')->toArray(),
                'type' => $animeLink->type ?? $apiData['type'], // ✅ Perbaikan di sini
                'status' => $apiData['status'],
                'studios' => $apiData['studios'],
                'genres' => $apiData['genres'],
                'episodes' => $apiData['episodes'],
                'duration' => $apiData['duration'],
                'aired' => $apiData['aired'],
                'score' => $apiData['score'],
            ];

            $downloadLinks = $animeLink->batches->flatMap->batchLinks;
        } else {
            // Tidak ada di database, gunakan API sepenuhnya
            $animeData = $apiData;
        }

        // Ambil rekomendasi anime dari Jikan
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

                if (count($recommendations) >= 12) {
                    break;
                }
            }
        }

        return view('anime.show', [
            'animeLink' => $animeLink,
            'anime' => $animeData,
            'downloadLinks' => $downloadLinks,
            'similarAnime' => $recommendations,
            'fromDatabase' => $fromDatabase,
        ]);
    }
}
