<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\AnimeLink;

class CommentController extends Controller
{
    public function index($anime_link_id)
    {
        $comments = Comment::where('anime_link_id', $anime_link_id)
                           ->latest()
                           ->get();

        return view('comments.index', compact('comments', 'anime_link_id'));
    }

    public function store(Request $request, $anime_link_id)
    {
        $request->validate([
            'body' => 'required|string',
            'author' => 'nullable|string|max:255',
        ]);

        Comment::create([
            'anime_link_id' => $anime_link_id,
            'user_id' => auth()->id(),
            'body' => $request->input('body'),
            'author' => $request->input('author'),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
