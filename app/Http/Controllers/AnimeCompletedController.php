<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimeLink;

class AnimeCompletedController extends Controller
{
    public function index(Request $request)
    {
        $rows = AnimeLink::with(['types', 'batches.batchLinks'])
            ->where('status', 'Finished Airing')
            ->get();

        $completedAnimes = $rows->map(function ($anime) {
            return [
                'mal_id'        => $anime->mal_id,
                'local_title'   => $anime->title,
                'title'         => $anime->title,
                'title_english' => $anime->title_english,
                'images'        => ['jpg' => ['image_url' => $anime->poster, 'large_image_url' => $anime->poster]],
                'image'         => $anime->poster,
                'duration'      => $anime->duration,
                'score'         => $anime->mal_score,
                'types'         => $anime->types->map(fn ($t) => [
                    'name'  => $t->name,
                    'color' => $t->color ?? '#6b7280',
                ])->values()->all(),
                'episodes'      => $anime->episodes,
                'batches'       => $anime->batches,
            ];
        })->values()->all();

        return view('anime.completed', [
            'animes' => $completedAnimes,
            'pagination' => [],
        ]);
    }
}
