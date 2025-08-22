<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AnimeDetailController extends Controller
{
    public function show($mal_id)
    {
        $animeLink = AnimeLink::with([
                'types',
                'batches' => fn ($q) => $q->has('batchLinks')->orderByDesc('created_at'),
                'batches.batchLinks',
                'comments' => fn ($q) => $q->latest(),
                'comments.user',
                'relatedGroup.relatedAnimes.animeLink.types',
            ])
            ->where('mal_id', $mal_id)
            ->first();

        if (!$animeLink) {
            // Jika tidak ditemukan berdasarkan MAL ID, coba redirect ke detail cartoon lokal
            return redirect()->route('cartoon.detail', $mal_id);
        }

        $animeLink->incrementQuietly('click_count');

        if (!$animeLink->mal_score || !$animeLink->imdb_score) {
            $this->updateScoresFromApi($animeLink);
        }

        $downloadLinks = $animeLink->batches->flatMap->batchLinks;
        $fromDatabase  = true;

        // --- Ambil data Jikan (untuk fallback berbagai field) ---
        $jikanData = Cache::remember("jikan_data_{$mal_id}", now()->addHours(6), function () use ($mal_id) {
            try {
                $response = Http::timeout(15)->get("https://api.jikan.moe/v4/anime/{$mal_id}/full");
                if (!$response->successful()) {
                    Log::error("Jikan API failed for MAL ID {$mal_id}", [
                        'status' => $response->status(),
                        'response' => $response->json()
                    ]);
                    return [];
                }
                return $response->json('data') ?? [];
            } catch (\Exception $e) {
                Log::error("Jikan API exception for MAL ID {$mal_id}: " . $e->getMessage());
                return [];
            }
        });

        if (empty($jikanData)) {
            abort(404, 'Anime not found via API');
        }

        // --- IMDb ---
        $imdbId = $animeLink->imdb_id ?? $this->extractImdbIdFromJikan($jikanData);

        $omdbData = [];
        if ($imdbId) {
            $omdbData = Cache::remember("omdb_data_{$imdbId}", now()->addDays(7), function () use ($imdbId) {
                try {
                    $response = Http::timeout(15)->get(config('services.omdb.url'), [
                        'apikey' => config('services.omdb.key'),
                        'i'      => $imdbId,
                    ]);
                    if (!$response->successful()) {
                        Log::warning("OMDb API failed for IMDb ID {$imdbId}", [
                            'status' => $response->status(),
                            'response' => $response->json()
                        ]);
                        return [];
                    }
                    return $response->json();
                } catch (\Exception $e) {
                    Log::error("OMDb API exception for IMDb ID {$imdbId}: " . $e->getMessage());
                    return [];
                }
            });
        }

        // --- Episodes: ambil dari DB dulu, kalau kosong fallback ke Jikan, lalu simpan ke DB ---
        $episodes = $animeLink->episodes; // kolom di tabel anime_links
        if (empty($episodes)) {
            $episodes = $jikanData['episodes'] ?? null;
            if (!empty($episodes)) {
                // simpan diam-diam agar next request tidak call API lagi
                $animeLink->episodes = $episodes;
                $animeLink->saveQuietly();
            }
        }

        $animeData = [
            'mal_id'         => $animeLink->mal_id,
            'title'          => $animeLink->title         ?? $jikanData['title'] ?? null,
            'title_english'  => $animeLink->title_english ?? $jikanData['title_english'] ?? null,
            'title_japanese' => $jikanData['title_japanese'] ?? null,
            'poster'         => $animeLink->poster        ?? $jikanData['images']['jpg']['large_image_url'] ?? null,
            'synopsis'       => $animeLink->synopsis      ?? $jikanData['synopsis'] ?? null,
            'season'         => $animeLink->season        ?? $jikanData['season'] ?? null,
            'year'           => $animeLink->year          ?? $jikanData['year'] ?? null,
            'type'           => $animeLink->type          ?? $jikanData['type'] ?? null,
            'status'         => $jikanData['status'] ?? 'Unknown',
            'studios'        => $jikanData['studios'] ?? [],
            'genres'         => $animeLink->genres
                                    ? explode(', ', $animeLink->genres)
                                    : collect($jikanData['genres'] ?? [])->pluck('name')->toArray(),
            'episodes'       => $episodes, // ← sudah DB-first
            'duration'       => $jikanData['duration'] ?? null,
            'aired'          => $jikanData['aired'] ?? [],
            'score'          => $animeLink->mal_score ?? $jikanData['score'] ?? null,
            'imdb_score'     => $animeLink->imdb_score ?? $omdbData['imdbRating'] ?? null,
            'imdb_id'        => $animeLink->imdb_id ?? $imdbId,
        ];

        // --- Rekomendasi ---
        $recommendations = Cache::remember("recommendations_{$mal_id}", now()->addHours(6), function () use ($mal_id) {
            $recs = [];
            try {
                $response = Http::timeout(15)->get("https://api.jikan.moe/v4/anime/{$mal_id}/recommendations");
                if ($response->successful()) {
                    foreach ($response['data'] as $item) {
                        if (isset($item['entry'])) {
                            $recs[] = [
                                'mal_id' => $item['entry']['mal_id'] ?? null,
                                'title'  => $item['entry']['title'] ?? 'Unknown',
                                'type'   => $item['entry']['type'] ?? 'Unknown',
                                'images' => $item['entry']['images'] ?? [],
                            ];
                        }
                        if (count($recs) >= 12) break;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed to get recommendations for MAL ID {$mal_id}: " . $e->getMessage());
            }
            return $recs;
        });

        return view('anime.show', [
            'animeLink'     => $animeLink,
            'anime'         => $animeData,
            'downloadLinks' => $downloadLinks,
            'similarAnime'  => $recommendations,
            'fromDatabase'  => $fromDatabase,
        ]);
    }

    protected function updateScoresFromApi(AnimeLink $animeLink)
    {
        $response = Http::get("https://api.jikan.moe/v4/anime/{$animeLink->mal_id}");
        if ($response->successful()) {
            $data = $response->json('data');

            if (isset($data['score'])) {
                $animeLink->mal_score = $data['score'];
            }

            // optional: sekalian isi episodes jika kosong
            if (empty($animeLink->episodes) && isset($data['episodes'])) {
                $animeLink->episodes = $data['episodes'];
            }

            if (!$animeLink->imdb_id && !empty($data['external_links'])) {
                foreach ($data['external_links'] as $link) {
                    if (str_contains($link['url'] ?? '', 'imdb.com/title/tt')) {
                        $animeLink->imdb_id = preg_replace('/^.*imdb\.com\/title\/(tt\d+).*$/', '$1', $link['url']);
                        break;
                    }
                }
            }

            if ($animeLink->imdb_id) {
                $omdb = Http::get(config('services.omdb.url'), [
                    'apikey' => config('services.omdb.key'),
                    'i'      => $animeLink->imdb_id,
                ]);

                if ($omdb->ok() && isset($omdb->json()['imdbRating'])) {
                    $animeLink->imdb_score = $omdb->json()['imdbRating'];
                }
            }

            $animeLink->save();
        }
    }

    private function extractImdbIdFromJikan(array $jikanData): ?string
    {
        if (!empty($jikanData['external_links'])) {
            foreach ($jikanData['external_links'] as $link) {
                if (str_contains($link['url'] ?? '', 'imdb.com/title/tt')) {
                    return preg_replace('/^.*imdb\.com\/title\/(tt\d+).*$/', '$1', $link['url']);
                }
            }
        }
        return null;
    }
}
