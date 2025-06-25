<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimeLink;
use Illuminate\Support\Facades\Http;

class AnimeCompletedController extends Controller
{
    private $jikanApiUrl = 'https://api.jikan.moe/v4/anime/';

    public function index(Request $request)
    {
        // Ambil semua anime dari database lokal
        $localAnimes = AnimeLink::with(['types', 'batches.batchLinks'])->get();

        $completedAnimes = [];

        // Loop setiap anime untuk cek status dari API
        foreach ($localAnimes as $anime) {
            $malId = $anime->mal_id;
            if (!$malId) continue;

            // Ambil detail dari API berdasarkan MAL ID
            $api = Http::get($this->jikanApiUrl . $malId);

            if ($api->successful()) {
                $data = $api->json('data');

                // Jika status dari API adalah Finished Airing
                if ($data['status'] === 'Finished Airing') {
                    $completedAnimes[] = array_merge(
                        $data,
                        [
                            'type' => $anime->types->first()->name ?? null,
                            'local_title' => $anime->title,
                            'batches' => $anime->batches ?? [],
                            // Tambahkan kolom lokal lainnya kalau perlu
                        ]
                    );
                }
            }
        }

        return view('anime.completed', [
            'animes' => $completedAnimes,
            'pagination' => [] // opsional jika kamu ingin paginate manual
        ]);
    }
}
