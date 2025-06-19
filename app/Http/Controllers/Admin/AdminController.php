<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadLink;
use App\Models\Spotlight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'total_users' => User::count(),
            'spotlights' => Spotlight::latest()->get(),
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function storeDownloadLink(Request $request, $mal_id)
    {
        $request->validate([
            'url' => 'required|url',
            'quality' => 'required|string'
        ]);

        DownloadLink::create([
            'mal_id' => $mal_id,
            'url' => $request->url,
            'quality' => $request->quality
        ]);

        return back()->with('success', 'Download link added successfully.');
    }

    public function deleteDownloadLink(DownloadLink $link)
    {
        $link->delete();
        return back()->with('success', 'Download link deleted.');
    }

    public function createSpotlight(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'mal_id' => 'required|integer'
        ]);

        Spotlight::create([
            'title' => $request->title,
            'mal_id' => $request->mal_id
        ]);

        return back()->with('success', 'Spotlight created successfully.');
    }

    public function deleteSpotlight(Spotlight $spotlight)
    {
        $spotlight->delete();
        return back()->with('success', 'Spotlight removed.');
    }

    public function manageDownloadLinks($mal_id)
    {
        $response = Http::get("https://api.jikan.moe/v4/anime/{$mal_id}/full");
        if (!$response->successful()) {
            return back()->withErrors('Anime not found in Jikan API.');
        }

        $anime = $response->json('data');
        $links = DownloadLink::where('mal_id', $mal_id)->get();

        return view('admin.manage-downloads', compact('anime', 'links'));
    }
}
