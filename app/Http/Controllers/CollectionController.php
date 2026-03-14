<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::latest()->paginate(24);
        return view('collections.index', compact('collections'));
    }

    public function show($slug)
    {
        $collection = Collection::where('slug', $slug)
            ->with([
                'animeLinks' => function ($query) {
                    $query->orderBy('year', 'asc')->orderBy('title', 'asc');
                }
            ])
            ->firstOrFail();

        return view('collections.show', compact('collection'));
    }
}
