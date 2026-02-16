<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnimeDisplayController extends Controller
{
    /**
     * Menampilkan tab "Work in Progress" dan "Recommendation"
     * ke halaman welcome.blade.php
     */
    public function showTabbed()
{
    $animes = Anime::select('id', 'mal_id', 'title', 'title_english', 'poster', 'background', 'type', 'genres', 'progress')
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($anime) {
            // Parse genres
            $anime->genres = is_string($anime->genres)
                ? array_map(fn($g) => ['name' => trim($g)], explode(',', $anime->genres))
                : [];

            // Ambil dari Jikan API (dengan cache)
            $api = cache()->remember("jikan_display_{$anime->mal_id}", 3600, function () use ($anime) {
                $response = Http::get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
                
                return $response->successful() ? $response->json('data') : null;
            });

            // Inject score dan episodes ke object Anime
            $anime->score = $api['score'] ?? null;
            $anime->episodes = $api['episodes'] ?? null;

            return $anime;
        })
        ->unique(fn($anime) => $anime->mal_id . '-' . $anime->type)
        ->values();

    // Untuk default background per tab
    $firstWork = $animes->firstWhere('type', 'work_in_progress');
    $firstRec  = $animes->firstWhere('type', 'recommendation');

    $bgWork = $firstWork ? asset('storage/' . $firstWork->background) : '';
    $bgRec  = $firstRec ? asset('storage/' . $firstRec->background) : '';

    $type = request('type', 'work_in_progress');
    $defaultBackground = $type === 'recommendation' ? $bgRec : $bgWork;

    return view('welcome', compact('animes', 'bgWork', 'bgRec', 'defaultBackground', 'type'));
}

    /**
     * JSON: semua data
     */
    public function all()
    {
        return response()->json(
            Anime::select('mal_id', 'title', 'poster', 'type', 'genres', 'progress')->get()
        );
    }

    /**
     * JSON: hanya Work in Progress
     */
    public function workInProgress()
    {
        return response()->json(
            Anime::where('type', 'work_in_progress')->get()
        );
    }

    /**
     * JSON: hanya Recommendation
     */
    public function recommendations()
    {
        return response()->json(
            Anime::where('type', 'recommendation')->get()
        );
    }

    /**
     * JSON: ambil berdasarkan ID lokal
     */
    public function byId($id)
    {
        return response()->json(
            Anime::findOrFail($id)
        );
    }

    /**
     * View tab berdasarkan query string ?type=
     */
    public function tabbed(Request $request)
    {
        $type = $request->query('type', 'work_in_progress');

        $animes = Anime::where('type', $type)
            ->select('id', 'mal_id', 'title', 'title_english', 'poster', 'type', 'genres', 'progress')
            ->get()
            ->map(function ($anime) {
                $anime->genres = array_map(fn($g) => ['name' => trim($g)], explode(',', $anime->genres));
                return $anime;
            })
            ->unique('mal_id')
            ->values();

        return view('welcome', compact('animes'));
    }
}
