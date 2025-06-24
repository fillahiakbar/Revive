<?php

namespace App\Http\Controllers;

use App\Models\AnimeLink; // <- Tambahkan baris ini
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class RssController extends Controller
{
    public function show($slug)
{
    $anime = AnimeLink::where('slug', $slug)->with(['batches.batchLinks'])->firstOrFail();

    $xml = view('rss.feed', compact('anime'))->render();

    return response($xml, 200)
        ->header('Content-Type', 'application/rss+xml')
        ->header('Cache-Control', 'no-cache');
}

}
