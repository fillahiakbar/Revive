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
        // Ambil semua anime dari database lokal
        $localAnimes = AnimeLink::with(['types', 'batches.batchLinks'])->get();

        $ongoingAnimes = [];

        // Loop setiap anime untuk cek status dari API
        foreach ($localAnimes as $anime) {
            $malId = $anime->mal_id;
            if (!$malId) continue;

            // Ambil detail dari API berdasarkan MAL ID
            $api = Http::get($this->jikanApiUrl . $malId);

            if ($api->successful()) {
                $data = $api->json('data');

                // Jika status anime dari API adalah airing, masukkan ke hasil
                if ($data['status'] === 'Currently Airing') {
                    $ongoingAnimes[] = array_merge(
                        $data,
                        [
                            'type' => $anime->types->first()->name ?? null,
                            'local_title' => $anime->title,
                            'batches' => $anime->batches ?? [],
                            // Bisa tambahkan field lokal lain dari database jika perlu
                        ]
                    );
                }
            }
        }

        return view('anime.ongoing', [
            'animes' => $ongoingAnimes,
            'pagination' => [] // pagination opsional, karena data dari database
        ]);
    }
}
