<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AnimeLink;

class AnimeListController extends Controller
{
    public function list(Request $request)
    {
        $letter = strtoupper($request->query('letter', 'ALL'));
        $page = (int) $request->query('page', 1);
        $perPage = 24;

        $query = AnimeLink::with('types');

        if ($letter !== 'ALL') {
            if ($letter === '0-9') {
                $query->whereRaw("LEFT(title, 1) REGEXP '^[0-9]'");
            } else {
                $query->whereRaw("LEFT(title, 1) = ?", [$letter]);
            }
        }

        $total = $query->count();

        $animes = $query->orderBy('title', 'asc')
                        ->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        $enhanced = $animes->map(function ($anime) {
            $malId = $anime->mal_id;

            $apiData = cache()->remember("jikan_mal_{$malId}", 3600, function () use ($malId) {
                $response = Http::get("https://api.jikan.moe/v4/anime/{$malId}");
                if ($response->successful()) {
                    return $response->json('data');
                }
                return null;
            });

            return [
                'mal_id'      => $anime->mal_id,
                'local_title' => $anime->title,
                'title'       => $apiData['title'] ?? $anime->title,
                'title_english' => $apiData['title_english'] ?? null,
                'images'      => [
                    'jpg' => [
                        'image_url' => $anime->poster ?: ($apiData['images']['jpg']['image_url'] ?? null),
                    ]
                ],
                'duration'    => $anime->duration ?? null,
                'score'       => $apiData['score'] ?? null,
                'types'       => $anime->types->map(fn($type) => [
                    'name' => $type->name,
                    'color' => $type->color ?? '#6b7280',
                ]),
                'episodes'    => $anime->episodes,
            ];
        });

        $paginated = new LengthAwarePaginator(
            $enhanced,
            $total,
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => $request->query(),
            ]
        );

        return view('anime.list', [
            'animes' => $paginated,
            'letter' => $letter,
        ]);
    }
}
