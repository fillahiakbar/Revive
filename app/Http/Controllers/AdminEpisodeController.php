<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Episode;

class AdminEpisodeController extends Controller
{
    public function index($mal_id)
    {
        $episodes = Episode::where('mal_id', $mal_id)->orderBy('episode_number')->get();
        return view('admin.episodes.index', compact('episodes', 'mal_id'));
    }

    public function create($mal_id)
    {
        return view('admin.episodes.create', compact('mal_id'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mal_id' => 'required|integer',
            'episode_number' => 'required|integer',
            'mp4_arabic_link' => 'nullable|url',
            'gdrive_link' => 'nullable|url',
            'mp4upload_link' => 'nullable|url',
            'torrent_link' => 'nullable|url',
        ]);

        Episode::create($data);
        return redirect()->route('admin.episodes.index', $data['mal_id'])->with('success', 'Episode added.');
    }

    public function edit(Episode $episode)
    {
        return view('admin.episodes.edit', compact('episode'));
    }

    public function update(Request $request, Episode $episode)
    {
        $data = $request->validate([
            'episode_number' => 'required|integer',
            'mp4_arabic_link' => 'nullable|url',
            'gdrive_link' => 'nullable|url',
            'mp4upload_link' => 'nullable|url',
            'torrent_link' => 'nullable|url',
        ]);

        $episode->update($data);
        return back()->with('success', 'Episode updated.');
    }

    public function destroy(Episode $episode)
    {
        $mal_id = $episode->mal_id;
        $episode->delete();
        return redirect()->route('admin.episodes.index', $mal_id)->with('success', 'Episode deleted.');
    }
}
