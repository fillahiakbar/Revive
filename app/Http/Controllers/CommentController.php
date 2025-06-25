<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
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
        ]);

        return back()->with('success', 'تمت إضافة التعليق بنجاح');
    }
}
