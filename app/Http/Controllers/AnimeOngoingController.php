<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnimeOngoingController extends Controller
{
    private $jikanApiUrl = 'https://api.jikan.moe/v4/anime';

    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $response = Http::get($this->jikanApiUrl, [
            'status' => 'airing',    // status ongoing
            'page' => $page,
            'limit' => 24,
            'sfw' => true
        ]);

        if (!$response->successful()) {
            return view('anime.ongoing', [
                'animes' => collect([]),
                'error' => 'Gagal mengambil data dari Jikan API.'
            ]);
        }

        $data = $response->json();

        return view('anime.ongoing', [
            'animes' => $data['data'],
            'pagination' => $data['pagination'] ?? []
        ]);
    }
}
